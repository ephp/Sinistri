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
        \Ephp\UtilityBundle\Controller\Traits\BaseController;

    const TPA = "/[a-z]{2}[a-z0-9]{1}( [a-z.]+[a-z]{1})?(\/|\-)[0-9]{2}\/[0-9]{1,3}/i";

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
     * @Route("/tpa-cron", name="imap_tpa", defaults={"_format"="json"})
     */
    public function tpaAction() {
        $this->openImap();
        $n = $this->countMessages();
        $messages = $this->getHeaders($n, min(25, $n));
        $body = $this->getBody($n);
        $subject = $body->getSubject();
        // Test TPA su subject
        preg_match(self::TPA, $subject, $tpa);
        if ($tpa) {
            Debug::vd('TPA', true);
            Debug::vd($tpa, true);
            Debug::vd($subject, true);
        }
//        Debug::vd($body);


        return array(
            'messages' => $messages,
        );
    }

    private function findScheda(\Ephp\ImapBundle\Entity\Body $body) {
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
            $scheda = $this->findOneBy('EphpSinistriBundle:Scheda', array('ospedale' => $ospedale->getId(), 'anno' => $token[1], 'tpa' => $token[2]));
            return $scheda;
        }
        return null;
    }

}
