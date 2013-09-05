<?php

namespace Ephp\Bundle\EmailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\UtilityBundle\Utility\Debug;

/**
 * @Route("/email")
 */
class CronController extends Controller {

    use \Ephp\ImapBundle\Controller\Traits\ImapController,
        \Ephp\UtilityBundle\Controller\Traits\BaseController,
        \Ephp\Bundle\SinistriBundle\Controller\Traits\SxCalendarController;

    const TPA = "/[a-z]{2}[a-z0-9]{1}( [a-z.]+[a-z]{1})?(\/|\-)[0-9]{2}\/[0-9]{1,3}/i";
    const RECD = "/RECD\-[0-9]+/i";

    /**
     * @Route("/countdown-cron", name="imap_countdown_cron", defaults={"_format"="json"})
     */
    public function countdownAction() {
        $this->openImap(null, null, null, null, null, 'Richiesta');
        $n = $this->countMessages();
        $out = array(
            'countdown' => $n,
            'subjects' => array(),
        );
        for ($i = 1; $i <= $n; $i++) {
            try {
                $this->getEm()->beginTransaction();
                $body = $this->getBody($i);
                $out['subjects'][] = $body->getSubject();
                $header = $body->getHeader();
                $this->persist($header);
                $body->setHeader($header);
                $attachs = $body->getAttach();
//                $body->getAttach()->clear();
                if (false) {
                    foreach ($attachs as $attach) {
                        /* @var $attach \Ephp\ImapBundle\Entity\Attach */
                        $attach->setData('');
                        $body->addAttach($attach);
                    }
                }
                $this->persist($body);
                foreach ($attachs as $attach) {
                    /* @var $attach \Ephp\ImapBundle\Entity\Attach */
                    $attach->setBody($body);
                    $this->persist($attach);
                }
                $countdown = new \Ephp\Bundle\EmailBundle\Entity\Countdown();
                $scheda = $this->findScheda($body);
                $countdown->setEmail($header);
                $countdown->setSendedAt($header->getDate());
                $countdown->setScheda($scheda);
                $countdown->setStato('N');
                $this->persist($countdown);
                $this->getEm()->commit();
            } catch (\Exception $e) {
                $this->getEm()->rollback();
                throw $e;
            }
        }
        for ($i = $n; $i > 0; $i--) {
            $this->deteteEmail($i);
        }
        $this->closeImap();

        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }

    /**
     * @Route("/risposte-cron", name="imap_risposte_cron", defaults={"_format"="json"})
     */
    public function risposteAction() {
        $this->openImap(null, null, null, null, null, 'Risposta');
        $n = $this->countMessages();
        $out = array(
            'risposte' => $n,
            'subjects' => array(),
        );
        for ($i = 1; $i <= $n; $i++) {
            try {
                $this->getEm()->beginTransaction();
                $body = $this->getBody($i);
                $out['subjects'][] = $body->getSubject();
                $subject = $body->getSubject();
                $s = $countdown = null;
                preg_match(self::RECD, $subject, $s);
                if (isset($s[0])) {
                    $token = substr($s[0], 5);
                    $countdown = $this->find('EphpEmailBundle:Countdown', $token);
                } else {
                    break;
                }
                /* @var $countdown \Ephp\Bundle\EmailBundle\Entity\Countdown */
                $header = $body->getHeader();
                $this->persist($header);
                $body->setHeader($header);
                $attachs = $body->getAttach();
//                $body->getAttach()->clear();
                if (false) {
                    foreach ($attachs as $attach) {
                        /* @var $attach \Ephp\ImapBundle\Entity\Attach */
                        $attach->setData('');
                        $body->addAttach($attach);
                    }
                }
                $this->persist($body);
                foreach ($attachs as $attach) {
                    /* @var $attach \Ephp\ImapBundle\Entity\Attach */
                    $attach->setBody($body);
                    $this->persist($attach);
                }
                $countdown->setRisposta($header);
                $this->persist($countdown);
                $this->getEm()->commit();
            } catch (\Exception $e) {
                $this->getEm()->rollback();
                throw $e;
            }
        }
        for ($i = $n; $i > 0; $i--) {
            $this->deteteEmail($i);
        }
        $this->closeImap();

        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }

    /**
     * @Route("/tpa-cron", name="imap_tpa", defaults={"_format"="json"})
     */
    public function tpaAction() {
        $out = array(
            'contec' => 0,
            'contec_subjects' => array(),
            'ravinale' => 0,
            'ravinale_subjects' => array(),
            'email' => 0,
            'email_subjects' => array(),
        );
        $this->openImap(null, null, null, null, null, 'Contec');
        $n = $this->countMessages();
        $out['contec'] = $n;
        for ($i = 1; $i <= $n; $i++) {
            try {
                $this->getEm()->beginTransaction();
                $body = $this->getBody($i);
                $out['contec_subjects'][] = $body->getSubject();
                $scheda = $this->findScheda($body, self::CLAIMANT_CONTEC);
                if ($scheda) {
                    if (is_array($scheda)) {
                        $schede = $scheda;
                        foreach ($schede as $scheda) {
                            $evento = $this->newEvento($this->EMAIL_JWEB, $scheda, $body->getSubject(), $body->getTxt());
                            $evento->setDataOra($body->getHeader()->getDate());
                            $this->persist($evento);
                            $this->notificaTpa($scheda, $evento);
                        }
                    } else {
                        $evento = $this->newEvento($this->EMAIL_JWEB, $scheda, $body->getSubject(), $body->getTxt());
                        $evento->setDataOra($body->getHeader()->getDate());
                        $this->persist($evento);
                        $this->notificaTpa($scheda, $evento);
                    }
                }
                $this->getEm()->commit();
            } catch (\Exception $e) {
                $this->getEm()->rollback();
                throw $e;
            }
        }
        for ($i = $n; $i > 0; $i--) {
            $this->deteteEmail($i);
        }
        $this->closeImap();

        $this->openImap(null, null, null, null, null, 'Ravinale');
        $n = $this->countMessages();
        $out['ravinale'] = $n;
        for ($i = 1; $i <= $n; $i++) {
            try {
                $this->getEm()->beginTransaction();
                $body = $this->getBody($i);
                $out['ravinale_subjects'][] = $body->getSubject();
                $scheda = $this->findScheda($body, self::CLAIMANT_RAVINALE);
                if ($scheda) {
                    if (is_array($scheda)) {
                        $schede = $scheda;
                        foreach ($schede as $scheda) {
                            $evento = $this->newEvento($this->EMAIL_RAVINALE, $scheda, $body->getSubject(), $body->getTxt());
                            $evento->setDataOra($body->getHeader()->getDate());
                            $this->persist($evento);
                            $this->notificaTpa($scheda, $evento);
                        }
                    } else {
                        $evento = $this->newEvento($this->EMAIL_RAVINALE, $scheda, $body->getSubject(), $body->getTxt());
                        $evento->setDataOra($body->getHeader()->getDate());
                        $this->persist($evento);
                        $this->notificaTpa($scheda, $evento);
                    }
                }
                $this->getEm()->commit();
            } catch (\Exception $e) {
                $this->getEm()->rollback();
                throw $e;
            }
        }
        for ($i = $n; $i > 0; $i--) {
            $this->deteteEmail($i);
        }
        $this->closeImap();

        $this->openImap(null, null, null, null, null, 'TPA');
        $n = $this->countMessages();
        $out['email'] = $n;
        for ($i = 1; $i <= $n; $i++) {
            try {
                $this->getEm()->beginTransaction();
                $body = $this->getBody($i);
                $out['email_subjects'][] = $body->getSubject();
                $scheda = $this->findScheda($body, self::CLAIMANT_TUTTI);
                if ($scheda) {
                    if (is_array($scheda)) {
                        $schede = $scheda;
                        foreach ($schede as $scheda) {
                            $evento = $this->newEvento($this->EMAIL_MANUALE, $scheda, $body->getSubject(), $body->getTxt());
                            $evento->setDataOra($body->getHeader()->getDate());
                            $this->persist($evento);
                            $this->notificaTpa($scheda, $evento);
                        }
                    } else {
                        $evento = $this->newEvento($this->EMAIL_MANUALE, $scheda, $body->getSubject(), $body->getTxt());
                        $evento->setDataOra($body->getHeader()->getDate());
                        $this->persist($evento);
                        $this->notificaTpa($scheda, $evento);
                    }
                }
                $this->getEm()->commit();
            } catch (\Exception $e) {
                $this->getEm()->rollback();
                throw $e;
            }
        }
        for ($i = $n; $i > 0; $i--) {
            $this->deteteEmail($i);
        }
        $this->closeImap();

        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }

    private function notificaTpa(\Ephp\Bundle\SinistriBundle\Entity\Scheda $scheda, \Ephp\Bundle\SinistriBundle\Entity\Evento $evento) {
        if ($scheda->getGestore()) {
            $message = \Swift_Message::newInstance()
                    ->setSubject("[NS] Nuova nota nella scheda " . $scheda)
                    ->setFrom($this->container->getParameter('email_robot'))
                    ->setTo(trim($scheda->getGestore()->getEmail()))
                    ->setBody($this->renderView("EphpEmailBundle:email:notifica_tpa.txt.twig", array('scheda' => $scheda, 'evento' => $evento)))
                    ->addPart($this->renderView("EphpEmailBundle:email:notifica_tpa.html.twig", array('scheda' => $scheda, 'evento' => $evento)), 'text/html');
            ;
            $message->getHeaders()->addTextHeader('X-Mailer', 'PHP v' . phpversion());
            $this->get('mailer')->send($message);
        }
    }

    const CLAIMANT_CONTEC = "Contec";
    const CLAIMANT_RAVINALE = "Ravinale";
    const CLAIMANT_TUTTI = array("Contec", "Ravinale");

    private function findScheda(\Ephp\ImapBundle\Entity\Body $body, $claimant = null) {
        $subject = $body->getSubject();
        $tpa = null;
        preg_match(self::TPA, $subject, $tpa);
        if (!$tpa) {
            $txt = $body->getTxt();
            preg_match(self::TPA, $txt, $tpa);
        }
        if (isset($tpa[0])) {
            $token = explode('/', $tpa[0]);
            if (count($token) == 2) {
                $token = array_merge(explode('-', $token[0]), array($token[1]));
            }
            $ospedale = $this->findOneBy('EphpSinistriBundle:Ospedale', array('sigla' => $token[0]));
            if (!$ospedale) {
                return null;
            }
            $scheda = $this->findOneBy('EphpSinistriBundle:Scheda', array('ospedale' => $ospedale->getId(), 'anno' => $token[1], 'tpa' => $token[2]));
            return $scheda;
        }
        if ($claimant) {
            if (!is_array($claimant)) {
                $claimant = array($claimant);
            }
            $claimants = $claimant;
            foreach ($claimants as $claimant) {
                $nomi = $this->getRepository('EphpSinistriBundle:Scheda')->nomi($claimant);
                $regexp = '/(' . implode('|', $nomi) . ')/i';
                $tpa = null;
                preg_match($regexp, $subject, $tpa);
                if (!$tpa) {
                    $txt = $body->getTxt();
                    preg_match($regexp, $txt, $tpa);
                }
                if (isset($tpa[0])) {
                    $schede = $this->findBy('EphpSinistriBundle:Scheda', array('claimant' => $tpa[0]));
                    if (count($schede) == 1) {
                        return $schede[0];
                    }
                    return $schede;
                }
            }
        }
        return null;
    }

}
