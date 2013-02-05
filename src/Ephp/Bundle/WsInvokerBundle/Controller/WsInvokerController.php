<?php

namespace Ephp\Bundle\WsInvokerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Ephp\Bundle\WsInvokerBundle\Entity\Sc31Request;
use Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication;
use Ephp\Bundle\WsInvokerBundle\Entity\Log\LogCall;

/**
 * @Route("/ws_invoker")
 */
class WsInvokerController extends Controller {

    /**
     * @Route("/", name="ws_index")
     * @Template()
     */
    public function indexAction() {
        return array();
    }

    /**
     * @Route("/user_login", name="ws_user_login")
     * @Template()
     */
    public function user_loginAction() {
        if ($this->getRequest()->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->getRequest()->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->getRequest()->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $this->getRequest()->getSession()->get(SecurityContext::LAST_USERNAME),
            'error' => $error,
        );
    }

    /**
     * @Route("/login_check", name="ws_login_check")
     */
    public function loginCheckAction() {
        
    }

    /**
     * @Route("/logout", name="ws_logout")
     */
    public function logoutAction() {
        
    }

    var $ch;
    var $persist = true;

    /**
     * Esegue un servizio fra quelli censiti nella tabella config_services
     * 
     * @param type $group_name
     * @param type $service_name
     * @param type $params
     * @return \Ephp\Bundle\WsInvokerBundle\Entity\Log\LogResponse 
     */
    protected function invokeService($group_name, $service_name, LogCall $call, $params = array(), $token = null) {
        $service = new \Ephp\Bundle\WsInvokerBundle\Entity\Config\Service();
        $log_request = new \Ephp\Bundle\WsInvokerBundle\Entity\Log\LogRequest();
        $log_response = new \Ephp\Bundle\WsInvokerBundle\Entity\Log\LogResponse();

        $em = $this->getEM();
        try {
            $em->beginTransaction();
            $repository = $em->getRepository('Ephp\Bundle\WsInvokerBundle\Entity\Config\Service');
            // Recupero il resvizio richiesto
            $service = $repository->getService($group_name, $service_name);

            if ($token) {
                $log_authentication = $this->checkAuthentication($token);
            } else {
                $log_authentication = $this->isAuthenticate();
            }
            $call->setAuth($log_authentication);
            if ($this->persist) {
                $em->persist($call);
                $em->flush();
            }
            $host = $service->getHost();
            $service->setLastCallAt(new \DateTime());
            $host->setLastCallAt(new \DateTime());

            $url = $this->replaceMarkup($service->getUrl($this->container->getParameter('running_mode') == 'prod'), $params, false);

            // Log della request
            $log_request->setCall($call);
            $log_request->setUrl($url);
            $log_request->setMethod($service->getMethod());
            $log_request->setSendedAt(new \DateTime());
            $xml = '';

            // Invio request e ricevo la response
            switch ($service->getMethod()) {
                case 'GET':
                case 'POST':
                case 'PUT':
                    $this->ch = curl_init();
                    $output = $this->callCurl($url, $service, $params, $log_request);
                    $status_code = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
                    $time = curl_getinfo($this->ch, CURLINFO_TOTAL_TIME);
                    curl_close($this->ch);
                    $output = str_replace("\r", "", $output);
                    $output = explode("\n\n", $output, 2);
                    $header = $output[0];
                    if (count($output) == 2) {
                        if (!strpos($output[0], 'OK')) {
                            $_output = explode("\n\n", $output[1], 2);
                            if (count($_output) == 2) {
                                $header = $_output[0];
                                $output[1] = $_output[1];
                            }
                        }
                        $response = $output[1];
                    } else {
                        $response = '';
                    }
                    break;
                case 'SOAP':
                    $start = microtime(true);
                    $output = $this->callSoap($url, $service, $params, $log_request);
                    $end = microtime(true);
                    $status_code = 200;
                    $time = ($end - $start);
                    $header = '';
                    $response = json_encode($output);
                    break;
            }
//            echo $output . "\n\n";
            // Log della response
            $log_response->setRequest($log_request);
            $log_response->setStatusCode($status_code);
            $log_response->setHeader($header);
            $log_response->setXml($response);
            $log_response->setReceivedAt(new \DateTime());
            $log_response->setTime($time);
            if ($this->persist) {
                $em->persist($log_response);
                $em->flush();

                $service->setLastStatusCode('200');
                $em->persist($service);
                $em->flush();

                $host->setLastStatusCode('200');
                $em->persist($host);
                $em->flush();

            }
            $em->commit();
        } catch (Exception $e) {
            $service->setLastStatusCode($e->getCode());
            $host->setLastStatusCode($e->getCode());
            $em->rollback();
            try {
                $em->beginTransaction();
                //Memorizzo comunque la request
                $em->persist($log_request);
                $em->flush();
                //E l'errore nella response
                $log_response->setRequest($log_request);
                $log_response->setStatusCode($e->getCode());
                $log_response->setHeader('');
                $log_response->setXml($e->getMessage());
                $log_response->setReceivedAt(new \DateTime());
                $log_response->setTime(0);
                if ($this->persist) {
                    $em->persist($log_response);
                    $em->flush();
                    //Aggiorno lo stato del servizio
                    $em->persist($service);
                    $em->flush();
                    //E lo stato dell'host
                    $em->persist($host);
                    $em->flush();
                }
                $em->commit();
            } catch (Exception $ex) {
                $em->rollback();
                throw $ex;
            }
            throw $e;
        }
        return $log_response;
    }

    protected function getServiceOutput($service_name) {
        $em = $this->getEM();
        $repository = $em->getRepository('Ephp\Bundle\WsInvokerBundle\Entity\Help\Service');
        $service = $repository->find($service_name);
        return $service->getOutput();
    }

    private function callCurl($url, \Ephp\Bundle\WsInvokerBundle\Entity\Config\Service $service, array $params, \Ephp\Bundle\WsInvokerBundle\Entity\Log\LogRequest $log_request) {
        $em = $this->getEM();
        $data = '';
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, $service->getMethod());
        if($this->container->getParameter('proxy.ip')) {
            curl_setopt($this->ch, CURLOPT_PROXY, $this->container->getParameter('proxy.ip'));
            curl_setopt($this->ch, CURLOPT_PROXYAUTH, $this->container->getParameter('proxy.ip'));
            curl_setopt($this->ch, CURLOPT_PROXYPORT, $this->container->getParameter('proxy.ip'));
            curl_setopt($this->ch, CURLOPT_PROXYTYPE, $this->container->getParameter('proxy.ip'));
            curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $this->container->getParameter('proxy.ip'));
        }
        $header = explode("\n", $this->replaceMarkup($service->getHeader(), $params, false));

        if ($service->getMethod() == 'POST' || $service->getMethod() == 'PUT') {
            $data = $this->replaceMarkup($service->getDataBody(), $params, false);
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
            switch(strtoupper($service->getDataType())) {
                case 'JSON':
                    $header[] = 'Content-type: application/json; charset=utf-8';
                    break;
                case 'X-WWW':
                default:
                    $header[] = 'Content-type: application/x-www-form-urlencoded; charset=utf-8';
                    break;
            }
            $header[] = 'Content-length: ' . strlen($data);
        }
//        \BringOut\Bundle\WebBundle\Functions\Funzioni::vd($header);
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
        $log_request->setHeader(implode("\n", $header));
        $log_request->setXml($data);
        if ($this->persist) {
            $em->persist($log_request);
            $em->flush();
        }
        return curl_exec($this->ch);
    }

    private function callSoap($url, \Ephp\Bundle\WsInvokerBundle\Entity\Config\Service $service, array $params, \Ephp\Bundle\WsInvokerBundle\Entity\Log\LogRequest $log_request) {
        $em = $this->getEM();
        $xml_string = $this->replaceMarkup($service->getDataBody(), $params, true, false);
        $xml = new \SimpleXMLElement($xml_string);
        $_xml = $this->xmlToArray($xml);

        $log_request->setXml($xml_string);
        if ($this->persist) {
            $em->persist($log_request);
            $em->flush();
        }
        $method = $service->getSoapMethod();

        try {
            $this->ch = new \SoapClient($url . '?wsdl', array());
            $return = $this->ch->$method($_xml);
        } catch (\SoapFault $ex) {
            throw $ex;
        } catch (\Exception $ex) {
            throw $ex;
        }

        return $return;
    }

    /**
     * Recupera il contenuto json o xml e lo prepara per la serializzazione
     * 
     * @param type $content
     * @return type 
     */
    protected function getContent($content = null) {
        if ($content === null) {
            $req = $this->getRequest();
            $content = $req->getContent();
        }
        if (substr($content, 0, 5) == '<?xml') {
            try {
                $xml = new \SimpleXMLElement($content);
                if ($xml->getName() != 'ws_invoke') {
                    throw new \Exception('Invalid XML request');
                }
                if ($xml->children()->getName() != 'data') {
                    throw new \Exception('Invalid WS_INVOKER request');
                }
                return $xml->children()->asXML();
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage() . ': ' . $content, 500);
            }
        }
        return $content;
    }

    /**
     * Invia una request interna a Symfony via POST
     *
     * @param Request $post_request
     * @return Response
     */
    protected function getPostResponse(Request $post_request) {
        return $this->container->get('http_kernel')->handle($post_request);
    }

    /**
     * Verifica che il token sia valido
     *
     * @param type $token
     * @return LogAuthentication
     * @throw SessionExpiredException
     */
    protected function checkAuthentication($token) {
        $em = $this->getEm();
        $repository = $em->getRepository('Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication');
        return $repository->checkToken($token);
    }

    /**
     * Cerca il token e se non esiste lo crea
     *
     * @param type $token
     * @return LogAuthentication
     * @throw SessionExpiredException
     */
    protected function isAuthenticate() {
        $em = $this->getEm();
        $repository = $em->getRepository('Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication');
        /* @var $repository \Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthenticationRepository */
        return $repository->checkOrCreateToken($this->getUtente(), $this->persist);
    }

    /**
     * @Route("test/service/get/{group_name}/{service_name}", name="service")
     * @Template()
     */
    public function testGetServiceAction($group_name, $service_name) {
        try {
            $response = $this->invokeService(true, $group_name, $service_name);
        } catch (Exception $e) {
            throw $e;
        }
        return array('service' => $service_name, 'status_code' => $response->getStatusCode(), 'time' => $response->getTime(), 'content' => $response->getXml());
    }

    /**
     * @Route("/help", name="help_root")
     * @Template("EphpWsInvokerBundle:WsInvoker:help.html.twig")
     */
    public function helpRootAction() {
        return array('services' => array(), 'group' => '- Manualistica');
    }

    /**
     * @Route("/help/{group_name}", name="help")
     * @Template("EphpWsInvokerBundle:WsInvoker:help.html.twig")
     */
    public function helpAction($group_name) {
        try {
            $em = $this->getEM();
            $r_group = $em->getRepository('Ephp\Bundle\WsInvokerBundle\Entity\Config\Group');
            $r_help = $em->getRepository('Ephp\Bundle\WsInvokerBundle\Entity\Help\Service');
            $group = $r_group->findOneBy(array('name' => $group_name));
            $services = $r_help->findBy(array('group' => $group->getId()));

            return array('services' => $services, 'group' => $group->getName());
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @Route("help/service/{service_name}", name="help_service")
     * @Template("EphpWsInvokerBundle:WsInvoker:helpService.html.twig")
     */
    public function helpServiceAction($service_name) {
        try {
            $em = $this->getEM();
            $repository = $em->getRepository('Ephp\Bundle\WsInvokerBundle\Entity\Help\Service');
            $service = $repository->find($service_name);

            return array('service' => $service);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @Route("/analyze", name="analyze_root")
     * @Template("EphpWsInvokerBundle:WsInvoker:analyze.html.twig")
     */
    public function analyzeAction() {
        try {
            $em = $this->getEM();
            $r_auth = $em->getRepository('Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication');
            $sessions = $r_auth->findBy(array(), array('id' => 'desc'));

            return array('sessions' => $sessions);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @Route("/analyze/{session_id}", name="analyze_session")
     * @Template("EphpWsInvokerBundle:WsInvoker:analyzeSession.html.twig")
     */
    public function analyzeSessionAction($session_id) {
        try {
            $em = $this->getEM();
            $repository = $em->getRepository('Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication');
            $session = $repository->find($session_id);

            return array('session' => $session);
        } catch (Exception $e) {
            throw $e;
        }
    }

    protected function renderErrorMessage(\Exception $e) {
        header("Content-type: text/xml");
        echo $this->getErrorMessage($e);
        exit;
    }

    protected function getErrorMessage(\Exception $e) {
        return "<?xml version=\"1.0\" encoding=\"ISO-8859-1\" ?>
<sc31_error>
    <data>
        <code>{$e->getCode()}</code>
        <error>{$e->getMessage()}</error>
    </data>
</sc31_error>
";
    }

    protected function replaceMarkup($string, array $params, $xml = true, $empty_tag = true) {
        foreach ($params as $key => $value) {
            if ($value !== null || !$empty_tag) {
                $string = str_replace("%{$key}%", $value ? : '', $string);
            } else {
                if ($xml) {
                    $string = preg_replace('/[ ]+<([^>]+)>%' . $key . '%<\/([^>]+)>
/', '', $string);
                    $string = str_replace("%{$key}%", '', $string);
                }
            }
        }
        if ($empty_tag) {
            $string = preg_replace('/<([^>\/]+)><\/([^>]+)>/', '', $string);
            $string = preg_replace('/<([^>\/]+)>([
 ]+)<\/([^>]+)>/', '', $string);
//        $string = preg_replace('/>([
// ]+)</', '>
// <', $string);
            $string = preg_replace('/
([ ]+)
/', '
', $string);
        }
        if ($xml) {
            try {
                $checkXml = new \SimpleXMLElement($string);
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage() . ': ' . $string, 500);
            }
        }
        return $string;
    }

    private $multiriga = array();

    /**
     * Serializza xml in array
     * 
     * @param \SimpleXMLElement $xml
     * @param string $prefix
     * @return array 
     */
    protected function xmlToArray(\SimpleXMLElement $xml, $prefix = '', $clear_cache = true) {
        if ($clear_cache) {
            $this->multiriga = array();
        }
        $out = array();

        foreach ($xml->children() as $foglia) {
            $id = ($prefix ? $prefix . '_' : '') . $foglia->getName();
            if (!isset($out[$id])) {
                $out[$id] = $foglia->count() == 0 ? "{$foglia}" : $this->xmlToArray($foglia, $prefix, false);
            } else {
                $index = $xml->getName() . '|' . $id;
                if (!in_array($index, $this->multiriga)) {
                    $this->multiriga[] = $index;
                    $out[$id] = array($out[$id]);
                }
                $out[$id][] = $foglia->count() == 0 ? "{$foglia}" : $this->xmlToArray($foglia, $prefix, false);
            }
        }

        return $out;
    }

    protected function getFromArray($array, $key_string, $required = false, $if_array = 'first') {
        $keys = explode('|', $key_string);
        $tmp = $array;
        foreach ($keys as $key) {
            if (!isset($tmp[$key])) {
                if ($required) {
                    throw new \Exception('Element [' . implode('][', $keys) . '] not exist');
                } else {
                    return null;
                }
            }
            $tmp = $tmp[$key];
        }
        if (is_array($tmp)) {
            switch ($if_array) {
                case 'first':
                    return isset($tmp[0]) ? $tmp[0] : null;
                case 'json':
                    return json_encode($tmp);
                case 'array':
                    return $tmp;
                default:
                    if (is_string($if_array)) {
                        return implode($if_array, $tmp);
                    }
                    throw new \Exception('Array not serialized');
            }
        } else {
            return $tmp;
        }
    }

    protected function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            $d = get_object_vars($d);
        }

        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return array_map(array(__CLASS__, __FUNCTION__), $d);
        } else {
            // Return array
            return $d;
        }
    }

    protected function arrayToObject($d) {
        if (is_array($d)) {
            /*
             * Return array converted to object
             * Using __FUNCTION__ (Magic constant)
             * for recursive call
             */
            return (object) array_map(array(__CLASS__, __FUNCTION__), $d);
        } else {
            // Return object
            return $d;
        }
    }

    /**
     * Crea una chiamata a un servizio censito in help
     * 
     * @param type $service_name
     * @return \Ephp\Bundle\WsInvokerBundle\Entity\Log\LogCall 
     */
    protected function createCall($service_name) {
        $em = $this->getEM();
        $zona = new \DateTimeZone('Europe/Rome');
        $repository = $em->getRepository('Ephp\Bundle\WsInvokerBundle\Entity\Help\Service');
        $service = $repository->find($service_name);
        $this->persist = $service->getEnableLog();
        try {
            $em->beginTransaction();
            $call = new LogCall();
            $call->setService($service);
            $call->setSendedAt(new \DateTime('now', $zona));
            if ($this->persist) {
                $em->persist($call);
                $em->flush();
            }
            $em->commit();
            return $call;
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
    }

    protected function completeCall(LogCall $call, $request, $response) {
        $em = $this->getEM();
        $zona = new \DateTimeZone('Europe/Rome');
        try {
            $em->beginTransaction();
            $call->setRequest($request);
            $call->setResponse($response);
            $now = new \DateTime('now', $zona);
            $call->setDuration($now->getTimestamp() - $call->getSendedAt()->getTimestamp());
            if ($this->persist) {
                $em->persist($call);
                $em->flush();
            }
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
    }

    /**
     * Entity Manager getter
     * 
     * Restituisce l'entity manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEM() {
        return $this->getDoctrine()->getEntityManager();
    }

    /**
     * Restituisce l'utente di sessione
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface 
     */
    public function getUtente() {
        $user = null;
        $securityHandler = $this->getSecurityHandler();
        if ($securityHandler !== null) {
            if ($securityHandler->getContainer() === null) {
                $securityHandler->setContainer($this->container);
            }
            $user = $securityHandler->getUser();
        } else {
            $securityContext = $this->get('security.context');
            if ($securityContext !== null) {
                $securityToken = $securityContext->getToken();
                if ($securityToken !== null) {
                    $user = $securityToken->getUser();
                }
            }
        }
        return $user;
    }

    /**
     * Restituisce il security handler per la gestione delle acl
     *
     * @return \Schema31\Components\Handler\SecurityHandlerInterface security handler
     */
    public function getSecurityHandler() {
        return $this->securityHandler;
    }

    /**
     * Imposta il security handler per la gestione delle acl
     * 
     * @param \Schema31\Components\Handler\SecurityHandlerInterface $securityHandler
     * @param boolean $securityCheckFields 
     */
    public function setSecurityHandler(SecurityHandlerInterface $securityHandler, $securityCheckFields = false) {
        $this->securityHandler = $securityHandler;
        $this->setSecurityCheckFields($securityCheckFields);
    }

    protected $securityHandler = null;

    /**
     * Indica se le ACL devono verificare o meno i diriti di accesso sui singoli campi
     *
     * @var boolean
     */
    public function getSecurityCheckFields() {
        return $this->securityCheckFields;
    }

    /**
     * Inizializza il Security Check Fields
     *
     * @param boolean $securityCheckFields 
     */
    public function setSecurityCheckFields($securityCheckFields) {
        $this->securityCheckFields = $securityCheckFields;
    }

    protected $securityCheckFields = true;

    /**
     * Controlla le autorizzazione dell'utente sull'oggetto
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param mixed $resource risorsa da verificare
     * @param mixed $field
     * @param \Symfony\Component\Security\Core\User\UserInterface $user utente su cui verranno verificati i diritti
     * @return type 
     */
    protected function isGranted($request, $resource, $field = null, $user = null) {
        $securityHandler = $this->getSecurityHandler();
        if ($securityHandler !== null) {
            if ($securityHandler->getContainer() === null) {
                $securityHandler->setContainer($this->container);
            }
            $id = null;
            if (\method_exists($resource, 'getId')) {
                $id = $resource->getId();
                if ($id === null) {
                    $id = 999999999;
                    $resource->setId($id);
                }
            }
            $isGranted = $securityHandler->isGranted($request, $resource, $field, $user);
            if ($id === 999999999) {
                $resource->setId(null);
            }
            return $isGranted;
        }
        return true;
    }

}
