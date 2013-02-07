<?php

namespace Ephp\Bundle\SinistriBundle\Controller;

use Ephp\Bundle\DragDropBundle\Controller\DragDropController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\ACLBundle\Entity\Gestore;
use Ephp\Bundle\CalendarBundle\Entity\Calendario;
use Ephp\Bundle\SinistriBundle\Entity\Evento;
use Ephp\Bundle\SinistriBundle\Entity\Priorita;
use Ephp\Bundle\SinistriBundle\Entity\Scheda;
use Ephp\Bundle\WsInvokerBundle\Functions\Funzioni;

/**
 * Scheda controller.
 *
 * @Route("/tabellone")
 */
class SchedaController extends DragDropController {

    /**
     * Lists all Scheda entities.
     *
     * @Route("/{ospedale}/{anno}", name="tabellone", defaults={"ospedale"="", "anno"=""})
     * @Template()
     */
    public function indexAction($ospedale, $anno) {
        $em = $this->getEm();
        $mode = 0;
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $_priorita = $em->getRepository('EphpSinistriBundle:Priorita');
        if ($ospedale && $anno) {
            $mode = 3;
            $ospedale = $_ospedale->findOneBy(array('sigla' => $ospedale));
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('ospedale' => $ospedale->getId(), 'anno' => $anno), array('anno' => 'ASC'));
        } elseif ($ospedale) {
            $mode = 2;
            $ospedale = $_ospedale->findOneBy(array('sigla' => $ospedale));
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('ospedale' => $ospedale->getId()), array('anno' => 'ASC'));
        } else {
            $mode = 1;
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array(), array('anno' => 'ASC'));
        }
        $ospedali = $_ospedale->findBy(array(), array('sigla' => 'ASC'));
        $gestori = $_gestore->findBy(array(), array('sigla' => 'ASC'));
        $priorita = $_priorita->findAll();
        return array(
            'entities' => $entities,
            'mode' => $mode,
            'ospedale' => $ospedale,
            'anno' => $anno < 10 ? '0' . $anno : $anno,
            'ospedali' => $ospedali,
            'gestori' => $gestori,
            'priorita' => $priorita,
            'anni' => range(7, date('y'))
        );
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-gestore_{gestore}/{ospedale}/{anno}", name="tabellone_gestore", defaults={"ospedale"="", "anno"=""})
     * @Template("EphpSinistriBundle:Scheda:index.html.twig")
     */
    public function gestoreAction($gestore, $ospedale, $anno) {
        $em = $this->getEm();
        $mode = 0;
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $_priorita = $em->getRepository('EphpSinistriBundle:Priorita');
        $gestore = $_gestore->findOneBy(array('sigla' => $gestore));
        if ($ospedale && $anno) {
            $mode = 3;
            $ospedale = $_ospedale->findOneBy(array('sigla' => $ospedale));
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId(), 'ospedale' => $ospedale->getId(), 'anno' => $anno), array('anno' => 'ASC'));
        } elseif ($ospedale) {
            $mode = 2;
            $ospedale = $_ospedale->findOneBy(array('sigla' => $ospedale));
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId(), 'ospedale' => $ospedale->getId()), array('anno' => 'ASC'));
        } else {
            $mode = 1;
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId()), array('anno' => 'ASC'));
        }
        $ospedali = $_ospedale->findBy(array(), array('sigla' => 'ASC'));
        $gestori = $_gestore->findBy(array(), array('sigla' => 'ASC'));
        $priorita = $_priorita->findAll();
        return array(
            'entities' => $entities,
            'mode' => $mode,
            'ospedale' => $ospedale,
            'anno' => $anno < 10 ? '0' . $anno : $anno,
            'gestore' => $gestore,
            'ospedali' => $ospedali,
            'gestori' => $gestori,
            'priorita' => $priorita,
            'anni' => range(7, date('y'))
        );
    }

    /**
     * Finds and displays a Scheda entity.
     *
     * @Route("-scheda/{id}", name="tabellone_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getEm();

        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);
        /* @var $entity Scheda */

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }

        if (count($entity->getEventi()) == 0) {
            try {
                $em->beginTransaction();
                $cal = $this->getCalendar();
                $generatore = array(
                    array('tipo' => 'ASC', 'giorni' => 0),
                    array('tipo' => 'VIM', 'giorni' => 10),
                    array('tipo' => 'RPM', 'giorni' => 25),
                    array('tipo' => 'RER', 'giorni' => 14),
                    array('tipo' => 'RSA', 'giorni' => 14),
                    array('tipo' => 'TAX', 'giorni' => 30),
                    array('tipo' => 'TAX', 'giorni' => 14),
                    array('tipo' => 'TAX', 'giorni' => 14)
                );
                $data = $entity->getDasc();
                foreach ($generatore as $i => $gen) {
                    $data = Funzioni::calcolaData($data, $gen['giorni']);
                    $tipo = $this->getTipoEvento($gen['tipo']);
                    $evento = new Evento();
                    $evento->setCalendario($cal);
                    $evento->setData($data);
                    $evento->setDataOra($data);
                    $evento->setDeltaG($gen['giorni']);
                    $evento->setGiornoIntero(true);
                    $evento->setImportante(true);
                    $evento->setNote('');
                    $evento->setOra($data);
                    $evento->setOrdine($i + 1);
                    $evento->setRischedulazione(true);
                    $evento->setScheda($entity);
                    $evento->setTipo($tipo);
                    $evento->setTitolo($tipo->getNome());
                    $em->persist($evento);
                    $em->flush();
                    $entity->addEventi($evento);
                }
                $em->commit();
            } catch (\Exception $e) {
                $em->rollback();
                throw $e;
            }
        }

        return array(
            'entity' => $entity,
        );
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-assegna-gestore-scheda", name="tabellone_assegna_gestore", defaults={"_format"="json"})
     */
    public function assegnaGestoreAction() {
        $req = $this->getRequest()->get('scheda');
        $em = $this->getEm();

        $_scheda = $em->getRepository('EphpSinistriBundle:Scheda');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');

        $scheda = $_scheda->find($req['id']);
        /* @var $scheda Scheda */
        $gestore = $_gestore->findOneBy(array('sigla' => $req['gestore']));
        /* @var $gestore Gestore */

        $genera = is_null($scheda->getGestore());
        try {
            $scheda->setGestore($gestore);
            $em->persist($scheda);
            $em->flush();
            if ($genera) {
                // TODO
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('redirect' => $this->generateUrl('tabellone_show', array('id' => $scheda->getId())))));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-cambia-priorita-scheda", name="tabellone_cambia_priorita", defaults={"_format"="json"})
     */
    public function cambiaPrioritaAction() {
        $req = $this->getRequest()->get('priorita');
        $em = $this->getEm();

        $_scheda = $em->getRepository('EphpSinistriBundle:Scheda');
        $_priorita = $em->getRepository('EphpSinistriBundle:Priorita');

        $scheda = $_scheda->find($req['id']);
        /* @var $scheda Scheda */
        $priorita = $_priorita->find($req['priorita']);
        /* @var $priorita Priorita */

        try {
            $scheda->setPriorita($priorita);
            $em->persist($scheda);
            $em->flush();
        } catch (\Exception $e) {
            throw $e;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('id' => $priorita->getId(), 'label' => $priorita->getPriorita(), 'css' => 'bg-' . $priorita->getCss(), 'js' => $priorita->getOnChange())));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-autoupdate", name="tabellone_autoupdate", defaults={"_format"="json"})
     */
    public function autoupdateAction() {
        $req = $this->getRequest()->get('pratica');
        $em = $this->getEm();

        $_scheda = $em->getRepository('EphpSinistriBundle:Scheda');
        $scheda = $_scheda->find($req['id']);
        /* @var $scheda Scheda */
        try {
            switch ($req['field']) {
                case 'firstReserve':
                    $scheda->setFirstReserve($req['value']);
                    break;
                case 'amountReserved':
                    $scheda->setAmountReserved($req['value']);
                    break;
                case 'sa':
                    $scheda->setSa($req['value']);
                    break;
                case 'offertaNostra':
                    $scheda->setOffertaNostra($req['value']);
                    break;
                case 'offertaLoro':
                    $scheda->setOffertaLoro($req['value']);
                    break;
                case 'recuperoOffertaNostra':
                    $scheda->setRecuperoOffertaNostra($req['value']);
                    break;
                case 'recuperoOffertaLoro':
                    $scheda->setRecuperoOffertaLoro($req['value']);
                    break;
                case 'note':
                    $scheda->setNote($req['value']);
                    break;
            }
            $em->persist($scheda);
            $em->flush();
        } catch (\Exception $e) {
            throw $e;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode($req));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-uploadSingle", name="tabellone_upload_single")
     * @Template("EphpDragDropBundle:DragDrop:single.html.php")
     */
    public function uploadSingleAction() {
        return $this->singleFile();
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-upload", name="tabellone_upload_drive")
     * @Template()
     */
    public function uploadAction() {
        return array();
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-import-drive", name="tabellone_import_drive", defaults={"_format"="json"})
     */
    public function importAction() {
        $colonne = array('id', 'gestore', 'dasc', 'tpa', 'claimant', 'soi', 'first reserve', 'amount reserved', 'stato', 'sa', 'offerta ns', 'offerta loro', 'priorita', 'recupero offerta ns', 'recupero offerta loro', 'claimant 2', 'gmail',);
        $uri = __DIR__ . '/../../../../../web' . str_replace(' ', '+', urldecode($this->getRequest()->get('file')));
        set_time_limit(3600);
        $em = $this->getEm();
        $conn = $em->getConnection();
        $schede_aggiunte = 0;
        $schede_aggiornate = 0;

        $_scheda = $em->getRepository('EphpSinistriBundle:Scheda');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $_stato = $em->getRepository('EphpSinistriBundle:Stato');
        $_priorita = $em->getRepository('EphpSinistriBundle:Priorita');

        if ($handle = fopen($uri, 'r')) {
            fgetcsv($handle, 0, ',');
            while ($dati = fgetcsv($handle, 0, ',')) {
                try {
                    $conn->beginTransaction();
                    $scheda = new Scheda();
                    foreach ($colonne as $index => $colonna) {
                        switch ($colonna) {
                            case 'gestore':
                                if ($dati[$index]) {
                                    $gestore = $_gestore->findOneBy(array('sigla' => $dati[$index]));
                                    $scheda->setGestore($gestore);
                                }
                                break;
                            case 'dasc':
                                if ($dati[$index]) {
                                    $dasc = \DateTime::createFromFormat('d/m/Y', $dati[$index]);
                                    $scheda->setDasc($dasc);
                                }
                                break;
                            case 'tpa':
                                $tpa = explode('/', $dati[$index]);
                                if (count($tpa) == 3) {
                                    $ospedale = $_ospedale->findOneBy(array('sigla' => $tpa[0]));
                                    $anno = $tpa[1];
                                    $tpa = $tpa[2];
                                    $scheda->setOspedale($ospedale);
                                    $scheda->setAnno($anno);
                                    $scheda->setTpa($tpa);
                                } else {
                                    break(3);
                                }
                                break;

                            case 'claimant':
                                $scheda->setClaimant($dati[$index]);
                                break;
                            case 'soi':
                                if ($dati[$index]) {
                                    $scheda->setSoi($dati[$index]);
                                }
                                break;
                            case 'first reserve':
                                if ($dati[$index]) {
                                    $scheda->setFirstReserve(str_replace(array('€', ' '), array('', ''), $dati[$index]));
                                }
                                break;
                            case 'amount reserved':
                                if ($dati[$index]) {
                                    $scheda->setAmountReserved(str_replace(array('€', ' '), array('', ''), $dati[$index]));
                                }
                                break;
                            case 'stato':
                                if ($dati[$index]) {
                                    $stato = $_stato->findOneBy(array('stato' => $dati[$index]));
                                    if (!$stato) {
                                        $stato = new \Ephp\Bundle\SinistriBundle\Entity\Stato();
                                        $stato->setStato($dati[$index]);
                                        $em->persist($stato);
                                        $em->flush();
                                    }
                                    $scheda->setStato($stato);
                                }
                                break;
                            case 'sa':
                                if ($dati[$index]) {
                                    $scheda->setSa(str_replace(array('€', ' '), array('', ''), $dati[$index]));
                                }
                                break;
                            case 'offerta ns':
                                if ($dati[$index]) {
                                    $scheda->setOffertaNostra(str_replace(array('€', ' '), array('', ''), $dati[$index]));
                                }
                                break;
                            case 'offerta loro':
                                if ($dati[$index]) {
                                    $scheda->setOffertaLoro(str_replace(array('€', ' '), array('', ''), $dati[$index]));
                                }
                                break;
                            case 'priorita':
                                if ($dati[$index]) {
                                    $priorita = $_priorita->findOneBy(array('priorita' => $dati[$index]));
                                    $scheda->setPriorita($priorita);
                                }
                                break;
                            case 'recupero offerta ns':
                                if ($dati[$index]) {
                                    $scheda->setRecuperoOffertaNostra($dati[$index]);
                                }
                                break;
                            case 'recupero offerta loro':
                                if ($dati[$index]) {
                                    $scheda->setRecuperoOffertaLoro($dati[$index]);
                                }
                                break;
                            default: break;
                        }
                    }
                    $old = $_scheda->findOneBy(array('ospedale' => $scheda->getOspedale()->getId(), 'anno' => $scheda->getAnno(), 'tpa' => $scheda->getTpa()));
                    /* @var $old Scheda */
                    if ($old) {
                        $old->setAmountReserved($scheda->getAmountReserved());
//                        $old->setClaimant($scheda->getClaimant());
                        $old->setDasc($scheda->getDasc());
                        $old->setFirstReserve($scheda->getFirstReserve());
//                        $old->setGestore($scheda->getGestore());
//                        $old->setOffertaLoro($scheda->getOffertaLoro());
//                        $old->setOffertaNostra($scheda->getOffertaNostra());
//                        $old->setPriorita($scheda->getPriorita());
//                        $old->setRecuperoOffertaLoro($scheda->getRecuperoOffertaLoro());
//                        $old->setRecuperoOffertaNostra($scheda->getRecuperoOffertaNostra());
//                        $old->setSa($scheda->getSa());
                        $old->setSoi($scheda->getSoi());
                        $old->setStato($scheda->getStato());
                        $em->persist($old);
                        $em->flush();
                        $schede_aggiornate++;
                    } else {
                        $em->persist($scheda);
                        $em->flush();
                        $schede_aggiunte++;
                    }
                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
//            unlink($uri);
        }

        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('schede_aggiunte' => $schede_aggiunte, 'schede_aggiornate' => $schede_aggiornate)));
    }

    private function getTipoEvento($sigla) {
        $cal = $this->getCalendar();
        $_tipo = $this->getEm()->getRepository('EphpCalendarBundle:Tipo');
        /* @var $_tipo \Ephp\Bundle\CalendarBundle\Entity\TipoRepository */
        return $_tipo->findOneBy(array('sigla' => $sigla, 'calendario' => $cal->getId()));
    }

    private function getCalendar() {
        $_cal = $this->getEm()->getRepository('EphpCalendarBundle:Calendario');
        /* @var $_cal \Ephp\Bundle\CalendarBundle\Entity\CalendarioRepository */
        $cal = $_cal->findOneBy(array('sigla' => 'JFC-SX'));
        if (!$cal) {
            $cal = $_cal->createCalendario('JFC-SX', 'JF-Claims Sinistri');
            $_tipo = $this->getEm()->getRepository('EphpCalendarBundle:Tipo');
            /* @var $_tipo \Ephp\Bundle\CalendarBundle\Entity\TipoRepository */
            $_tipo->createTipo('ASC', 'Analisi Sinistri e Copertura', $cal);
            $_tipo->createTipo('VIM', 'Verifica Incarichi e Medici', $cal);
            $_tipo->createTipo('RPM', 'Ricerca Polizze e Medici', $cal);
            $_tipo->createTipo('RER', 'Relazione e Riserva', $cal);
            $_tipo->createTipo('RSA', 'Richiesta di SA', $cal);
            $_tipo->createTipo('TAX', 'Trattative e Aggiornamenti', $cal);
            $_tipo->createTipo('JWB', 'J-Web Claims', $cal);
            $_tipo->createTipo('JUD', 'Udienze', $cal);
            $_tipo->createTipo('OTH', 'Attività manuali', $cal);
        }
        return $cal;
    }

}

