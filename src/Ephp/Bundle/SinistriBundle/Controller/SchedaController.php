<?php

namespace Ephp\Bundle\SinistriBundle\Controller;

use Ephp\Bundle\DragDropBundle\Controller\DragDropController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\ACLBundle\Entity\Gestore;
use Ephp\Bundle\CalendarBundle\Entity\Calendario;
use Ephp\Bundle\SinistriBundle\Entity\Evento;
use Ephp\Bundle\SinistriBundle\Entity\Ospedale;
use Ephp\Bundle\SinistriBundle\Entity\Priorita;
use Ephp\Bundle\SinistriBundle\Entity\Scheda;
use Ephp\Bundle\WsInvokerBundle\Functions\Funzioni;
use Ephp\Bundle\WsInvokerBundle\PhpExcel\SpreadsheetExcelReader;

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
            $ospedali = $_ospedale->findBy(array('gruppo' => $ospedale));
            $ospedali_id = array();
            foreach ($ospedali as $ospedale) {
                $ospedali_id[] = $ospedale->getId();
            }
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('ospedale' => $ospedali_id, 'anno' => $anno), array('anno' => 'ASC'), 200);
        } elseif ($ospedale) {
            $mode = 2;
            $ospedali = $_ospedale->findBy(array('gruppo' => $ospedale));
            $ospedali_id = array();
            foreach ($ospedali as $ospedale) {
                $ospedali_id[] = $ospedale->getId();
            }
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('ospedale' => $ospedali_id), array('anno' => 'ASC'), 200);
        } else {
            $mode = 1;
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array(), array('anno' => 'ASC'), 200);
        }
        $ospedali = $_ospedale->findBy(array(), array('gruppo' => 'ASC'));
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
            $ospedali = $_ospedale->findBy(array('gruppo' => $ospedale));
            $ospedali_id = array();
            foreach ($ospedali as $ospedale) {
                $ospedali_id[] = $ospedale->getId();
            }
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId(), 'ospedale' => $ospedali_id, 'anno' => $anno), array('anno' => 'ASC'), 200);
        } elseif ($ospedale) {
            $mode = 2;
            $ospedali = $_ospedale->findBy(array('gruppo' => $ospedale));
            $ospedali_id = array();
            foreach ($ospedali as $ospedale) {
                $ospedali_id[] = $ospedale->getId();
            }
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId(), 'ospedale' => $ospedali_id), array('anno' => 'ASC'), 200);
        } else {
            $mode = 1;
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array('gestore' => $gestore->getId()), array('anno' => 'ASC'), 200);
        }
        $ospedali = $_ospedale->findBy(array(), array('gruppo' => 'ASC'));
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
     * Lists all Scheda entities.
     *
     * @Route("-cerca-{q}/{gestore}/{ospedale}/{anno}", name="tabellone_cerca", defaults={"gestore"="TUTTI", "ospedale"="TUTTI", "anno"="TUTTI", "q"=""})
     * @Template("EphpSinistriBundle:Scheda:index.html.twig")
     */
    public function cercaAction($q, $gestore, $ospedale, $anno) {
        if ($q == '') {
            $this->redirect($this->generateUrl('tabellone'));
        }
        $em = $this->getEm();
        $mode = 0;
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $_priorita = $em->getRepository('EphpSinistriBundle:Priorita');
        if ($gestore != 'TUTTI') {
            $gestore = $_gestore->findOneBy(array('sigla' => $gestore));
        } else {
            $gestore = false;
        };
        $ospedali_id = array();
        if ($ospedale != 'TUTTI') {
            $ospedali = $_ospedale->findBy(array('gruppo' => $ospedale));
            foreach ($ospedali as $ospedale) {
                $ospedali_id[] = $ospedale->getId();
            }
        } else {
            $ospedale = false;
        };
        if ($anno == 'TUTTI') {
            $anno = false;
        };
        if ($ospedale && $anno) {
            $mode = 3;
        } elseif ($ospedale) {
            $mode = 2;
        } else {
            $mode = 1;
        }
        $entities = $em->getRepository('EphpSinistriBundle:Scheda')->cerca($gestore, $ospedali_id, $anno, $q);
        $ospedali = $_ospedale->findBy(array(), array('gruppo' => 'ASC'));
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
            'anni' => range(7, date('y')),
            'q' => $q,
        );
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-scroll_{pagina}/{gestore}/{ospedale}/{anno}", name="tabellone_scroll")
     * @Template("EphpSinistriBundle:Scheda:index/tbody.html.twig")
     */
    public function scrollAction($pagina, $gestore, $ospedale, $anno) {
        $em = $this->getEm();
        $mode = 1;
        if ($ospedale != 'TUTTI' && $anno != 'TUTTI') {
            $mode = 3;
        } elseif ($ospedale != 'TUTTI') {
            $mode = 2;
        }
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $params = array();
        if ($anno != 'TUTTI') {
            $params['anno'] = $anno;
        }
        if ($ospedale != 'TUTTI') {
            $ospedale = $_ospedale->findOneBy(array('sigla' => $ospedale));
            $params['ospedale'] = $ospedale->getId();
        }
        if ($gestore != 'TUTTI') {
            $gestore = $_gestore->findOneBy(array('sigla' => $gestore));
            $params['gestore'] = $gestore->getId();
        }
        $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy($params, array('anno' => 'ASC'), 200, 200 * ($pagina - 1));
        return array(
            'gestore' => $gestore == 'TUTTI' ? null : $gestore,
            'entities' => $entities,
            'mode' => $mode,
            'index' => 200 * ($pagina - 1) + 1,
            'scroll' => true,
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
                    $evento->setDataOra($data);
                    $evento->setDeltaG($gen['giorni']);
                    $evento->setGiornoIntero(true);
                    $evento->setImportante(true);
                    $evento->setNote('');
                    $evento->setOrdine($i + 1);
                    $evento->setRischedulazione(true);
                    $evento->setScheda($entity);
                    $evento->setTipo($tipo);
                    $evento->setTitolo($tipo->getNome());
                    $em->persist($evento);
                    $em->flush();
                }
                $em->commit();
                return $this->redirect($this->generateUrl('tabellone_show', array('id' => $id)));
            } catch (\Exception $e) {
                $em->rollback();
                throw $e;
            }
        }

        return array(
            'entity' => $entity,
            'tipi' => $em->getRepository('EphpCalendarBundle:Tipo')->findBy(array('calendario' => $this->getEm()->getRepository('EphpCalendarBundle:Calendario')->findOneBy(array('sigla' => 'JFC-SX'))->getId())),
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
                case 'dasc':
                    $dasc = \DateTime::createFromFormat('d/m/Y', $req['value']);
                    $scheda->setDasc($dasc);
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
     * @Route("-autoupdate-calendario", name="tabellone_calendario_autoupdate", defaults={"_format"="json"})
     */
    public function autoupdateCalendarioAction() {
        $req = $this->getRequest()->get('evento');
        $req['reload'] = 0;
        $em = $this->getEm();

        $_evento = $em->getRepository('EphpSinistriBundle:Evento');
        $evento = $_evento->find($req['id']);
        /* @var $evento Evento */
        try {
            $em->beginTransaction();
            switch ($req['field']) {
                case 'titolo':
                    $evento->setTitolo($req['value']);
                    break;
                case 'data':
                    if ($req['value'] == '') {
                        $em->remove($evento);
                        $em->flush();
                        $req['reload'] = 1;
                    } else {
                        $old_data = $evento->getDataOra();
                        $data = \DateTime::createFromFormat('d/m/Y', $req['value']);
                        $evento->setDataOra($data);
                        $generatore = array(
                            array('tipo' => 'ASC', 'giorni' => 0),
                            array('tipo' => 'VIM', 'giorni' => 10),
                            array('tipo' => 'RPM', 'giorni' => 25),
                            array('tipo' => 'RER', 'giorni' => 14),
                            array('tipo' => 'RSA', 'giorni' => 14),
                            array('tipo' => 'TAX', 'giorni' => 30),
                            array('tipo' => 'TAX', 'giorni' => 14, 'from' => 1),
                            array('tipo' => 'TAX', 'giorni' => 14, 'from' => 2)
                        );
                        $rischedulato = false;
                        foreach ($generatore as $i => $gen) {
                            if ($rischedulato) {
                                $data = Funzioni::calcolaData($data, $gen['giorni']);
                                $tipo = $this->getTipoEvento($gen['tipo']);
                                if (isset($gen['from'])) {
                                    $eventoP = $_evento->findBy(array('scheda' => $evento->getScheda()->getId(), 'tipo' => $tipo->getId()), array(), 1, $gen['from']);
                                    $eventoP = $eventoP[0];
                                } else {
                                    $eventoP = $_evento->findOneBy(array('scheda' => $evento->getScheda()->getId(), 'tipo' => $tipo->getId()));
                                }
                                $eventoP->setDataOra($data);
                                $em->persist($eventoP);
                                $em->flush();
                            }
                            if ($evento->getTipo()->getSigla() == $gen['tipo']) {
                                if (!$rischedulato) {
                                    $rischedulato = true;
                                    $oggi = new \DateTime();
                                    $cal = $this->getCalendar();
                                    $tipo = $this->getTipoEvento('RIS');
                                    $eventoR = new Evento();
                                    $eventoR->setCalendario($cal);
                                    $eventoR->setDataOra($oggi);
                                    $eventoR->setDeltaG(0);
                                    $eventoR->setGiornoIntero(true);
                                    $eventoR->setImportante(false);
                                    $eventoR->setNote("{$evento->getTipo()->getNome()} (da " . date('d-m-Y', $old_data->getTimestamp()) . " a " . date('d-m-Y', $data->getTimestamp()) . ")");
                                    $eventoR->setOrdine($i + 1);
                                    $eventoR->setRischedulazione(false);
                                    $eventoR->setScheda($evento->getScheda());
                                    $eventoR->setTipo($tipo);
                                    $eventoR->setTitolo('Rischedulazione');
                                    $em->persist($eventoR);
                                    $em->flush();
                                    $req['reload'] = 1;
                                }
                            }
                        }
                    }
                    break;
                case 'note':
                    $evento->setNote($req['value']);
                    break;
            }
            $em->persist($evento);
            $em->flush();
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode($req));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-uploadXlsSingle", name="tabellone_xls_upload_single")
     * @Template("EphpDragDropBundle:DragDrop:singleXls.html.php")
     */
    public function uploadXlsSingleAction() {
        return $this->singleFile();
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-upload-xls-default-{tipo}", name="tabellone_upload_xls_default", defaults={"tipo"="default"})
     * @Route("-upload-xls-piemonte-{tipo}", name="tabellone_upload_xls_piemonte", defaults={"tipo"="piemonte"})
     * @Template()
     */
    public function uploadXlsAction($tipo) {
        return array('tipo' => $tipo);
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
     * @Route("-upload-default-{tipo}", name="tabellone_upload_drive_default", defaults={"tipo"="default"})
     * @Route("-upload-piemonte-{tipo}", name="tabellone_upload_drive_piemonte", defaults={"tipo"="piemonte"})
     * @Template()
     */
    public function uploadAction($tipo) {
        return array('tipo' => $tipo);
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-aggiungi-evento/{id}", name="tabellone_aggiungi_evento")
     * @Template("EphpSinistriBundle:Scheda:show/tabella.html.twig")
     */
    public function aggiungiEventoAction($id) {
        $req = $this->getRequest()->get('evento');
        $em = $this->getEm();
        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);
        /* @var $entity Scheda */
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }

        $data = \DateTime::createFromFormat('d/m/Y', $req['data']);
        $cal = $this->getCalendar();
        $tipo = $this->getTipoEvento('OTH');
        $evento = new Evento();
        $evento->setCalendario($cal);
        $evento->setDataOra($data);
        $evento->setDeltaG(0);
        $evento->setGiornoIntero(true);
        $evento->setImportante(false);
        $evento->setNote($req['note']);
        $evento->setOrdine(0);
        $evento->setRischedulazione(false);
        $evento->setScheda($entity);
        $evento->setTipo($tipo);
        $evento->setTitolo($req['titolo']);
        $em->persist($evento);
        $em->flush();
        return array('entity' => $em->getRepository('EphpSinistriBundle:Scheda')->find($id));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-calendario-google/{id}", name="calendario_import_google")
     * @Template("EphpSinistriBundle:Scheda:show/tabella.html.twig")
     */
    public function calGoogleAction($id) {
        $colonne = array('id', 'tipo', 'data', 'titolo', 'note');
        set_time_limit(3600);
        $em = $this->getEm();
        $conn = $em->getConnection();
        $req = $this->getRequest()->get('import');
        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);
        /* @var $entity Scheda */
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }

        $cal = $this->getCalendar();
        $csv = $req['csv'];
        $righe = explode("\n", str_replace(array("\r", "\n\n"), array("\n", "\n"), $csv));
        foreach ($righe as $riga) {
            $dati = explode("\t", $riga);
            if (count($dati) > 5 && $dati[3] && !in_array($dati[1], array('ASC', 'VIM', 'RPM', 'RER', 'RSA', 'TA1', 'TA2', 'TA3')))
                try {
                    $conn->beginTransaction();
                    $data = \DateTime::createFromFormat('d/m/Y', $dati[2]);
                    $tipo = $this->getTipoEvento($dati[1] ? str_replace('SIN', 'CNT', $dati[1]) : 'OTH');
                    $evento = new Evento();
                    $evento->setCalendario($cal);
                    $evento->setDataOra($data);
                    $evento->setDeltaG(0);
                    $evento->setGiornoIntero(true);
                    $evento->setImportante(false);
                    $evento->setNote($dati[4]);
                    $evento->setOrdine(0);
                    $evento->setRischedulazione(false);
                    $evento->setScheda($entity);
                    $evento->setTipo($tipo);
                    $evento->setTitolo($dati[3]);

                    $like = array(
                        'calendario' => $cal->getId(),
                        'tipo' => $tipo->getId(),
                        'scheda' => $entity->getId(),
                        'titolo' => $evento->getTitolo(),
                    );
                    if (in_array($tipo->getSigla(), array('JWB', 'CNT', 'RVP', 'RIS'))) {
                        $like['note'] = $evento->getNote();
                    }

                    $olds = $em->getRepository('EphpSinistriBundle:Evento')->findBy($like);
//                    Funzioni::vd($old);
                    if (!$olds) {
                        $em->persist($evento);
                        $em->flush();
                    } else {
                        $data->setTime(0, 0, 0);
                        $save = true;
                        foreach ($olds as $old) {
                            $old->getDataOra()->setTime(0, 0, 0);
                            if ($data->getTimestamp() == $old->getDataOra()->getTimestamp()) {
                                $save = false;
                                if ($tipo->getSigla() == 'OTH') {
                                    $old->setNote($dati[4]);
                                    $em->persist($old);
                                    $em->flush();
                                }
                            }
                        }
                        if ($save) {
                            $em->persist($evento);
                            $em->flush();
                        }
                    }

                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
        }
        return array('entity' => $em->getRepository('EphpSinistriBundle:Scheda')->find($id));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-calendario-jweb/{id}", name="calendario_import_jweb")
     * @Template("EphpSinistriBundle:Scheda:show/tabella.html.twig")
     */
    public function calJWebAction($id) {
        $colonne = array('data', 'autore', 'titolo', 'note');
        set_time_limit(3600);
        $em = $this->getEm();
        $conn = $em->getConnection();
        $req = $this->getRequest()->get('import');
        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);
        /* @var $entity Scheda */
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }

        $cal = $this->getCalendar();
        $csv = $req['csv'];
        $righe = explode("\n", str_replace(array("\r", "\n\n"), array("\n", "\n"), $csv));
        foreach ($righe as $riga) {
            $dati = explode("\t", $riga);
            if (count($dati) >= 4) {
                try {
                    $conn->beginTransaction();
                    $data = \DateTime::createFromFormat('d/m/Y', substr($dati[0], 0, 10));
                    /* @var $data \DateTime */
                    $tipo = $this->getTipoEvento('JWB');
                    $evento = new Evento();
                    $evento->setCalendario($cal);
                    $evento->setDataOra($data);
                    $evento->setDeltaG(0);
                    $evento->setGiornoIntero(true);
                    $evento->setImportante(false);
                    $evento->setNote($dati[3] . ($dati[1] ? "({$dati[1]})" : ''));
                    $evento->setOrdine(0);
                    $evento->setRischedulazione(false);
                    $evento->setScheda($entity);
                    $evento->setTipo($tipo);
                    $evento->setTitolo($dati[2]);
                    $olds = $em->getRepository('EphpSinistriBundle:Evento')->findBy(array(
                        'calendario' => $cal->getId(),
                        'tipo' => $tipo->getId(),
                        'scheda' => $entity->getId(),
                        'titolo' => $evento->getTitolo(),
                        'note' => $evento->getNote(),
                            ));
//                    Funzioni::vd($old);
                    if (!$olds) {
                        $em->persist($evento);
                        $em->flush();
                    } else {
                        $data->setTime(0, 0, 0);
                        $save = true;
                        foreach ($olds as $old) {
                            $old->getDataOra()->setTime(0, 0, 0);
                            if ($data->getTimestamp() == $old->getDataOra()->getTimestamp()) {
                                $save = false;
                            }
                        }
                        if ($save) {
                            $em->persist($evento);
                            $em->flush();
                        }
                    }

                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
        }
        return array('entity' => $em->getRepository('EphpSinistriBundle:Scheda')->find($id));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-import-cancelleria/{id}", name="calendario_import_cancelleria")
     * @Template("EphpSinistriBundle:Scheda:show/tabella.html.twig")
     */
    public function calCancelleriaTelematicaAction($id) {
        $colonne = array('data', 'titolo', 'note');
        set_time_limit(3600);
        $em = $this->getEm();
        $conn = $em->getConnection();
        $req = $this->getRequest()->get('import');
        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);
        /* @var $entity Scheda */
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }

        $cal = $this->getCalendar();
        $csv = $req['csv'];
        $righe = explode("\n", str_replace(array("\r", "\n\n"), array("\n", "\n"), $csv));
        foreach ($righe as $riga) {
            $dati = explode("\t", $riga);
            if (count($dati) >= 3) {
                try {
                    $conn->beginTransaction();
                    $data = \DateTime::createFromFormat('d/m/Y', substr($dati[0], 0, 10));
                    /* @var $data \DateTime */
                    $tipo = $this->getTipoEvento('CNT');
                    $evento = new Evento();
                    $evento->setCalendario($cal);
                    $evento->setDataOra($data);
                    $evento->setDeltaG(0);
                    $evento->setGiornoIntero(true);
                    $evento->setImportante(false);
                    $evento->setNote($dati[2]);
                    $evento->setOrdine(0);
                    $evento->setRischedulazione(false);
                    $evento->setScheda($entity);
                    $evento->setTipo($tipo);
                    $evento->setTitolo($dati[1]);
                    $olds = $em->getRepository('EphpSinistriBundle:Evento')->findBy(array(
                        'calendario' => $cal->getId(),
                        'tipo' => $tipo->getId(),
                        'scheda' => $entity->getId(),
                        'titolo' => $evento->getTitolo(),
                        'note' => $evento->getNote(),
                            ));
//                    Funzioni::vd($old);
                    if (!$olds) {
                        $em->persist($evento);
                        $em->flush();
                    } else {
                        $data->setTime(0, 0, 0);
                        $save = true;
                        foreach ($olds as $old) {
                            $old->getDataOra()->setTime(0, 0, 0);
                            if ($data->getTimestamp() == $old->getDataOra()->getTimestamp()) {
                                $save = false;
                            }
                        }
                        if ($save) {
                            $em->persist($evento);
                            $em->flush();
                        }
                    }

                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
        }
        return array('entity' => $em->getRepository('EphpSinistriBundle:Scheda')->find($id));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-import-ravinale/{id}", name="calendario_import_ravinale")
     * @Template("EphpSinistriBundle:Scheda:show/tabella.html.twig")
     */
    public function calRavinaleTelematicaAction($id) {
        $colonne = array('data', 'note');
        set_time_limit(3600);
        $em = $this->getEm();
        $conn = $em->getConnection();
        $req = $this->getRequest()->get('import');
        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);
        /* @var $entity Scheda */
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }

        $cal = $this->getCalendar();
        $csv = $req['csv'];
        $righe = explode("\n", str_replace(array("\r", "\n\n"), array("\n", "\n"), $csv));
        foreach ($righe as $riga) {
            $dati = explode("\t", $riga);
            if (count($dati) >= 2) {
                try {
                    $conn->beginTransaction();
                    $data = \DateTime::createFromFormat('d/m/Y', substr($dati[0], 0, 10));
                    /* @var $data \DateTime */
                    $tipo = $this->getTipoEvento('RVP');
                    $evento = new Evento();
                    $evento->setCalendario($cal);
                    $evento->setDataOra($data);
                    $evento->setDeltaG(0);
                    $evento->setGiornoIntero(true);
                    $evento->setImportante(false);
                    $evento->setNote($dati[1]);
                    $evento->setOrdine(0);
                    $evento->setRischedulazione(false);
                    $evento->setScheda($entity);
                    $evento->setTipo($tipo);
                    $evento->setTitolo('Ravinale Piemonte');
                    $olds = $em->getRepository('EphpSinistriBundle:Evento')->findBy(array(
                        'calendario' => $cal->getId(),
                        'tipo' => $tipo->getId(),
                        'scheda' => $entity->getId(),
                        'note' => $evento->getNote(),
                            ));
//                    Funzioni::vd($old);
                    if (!$olds) {
                        $em->persist($evento);
                        $em->flush();
                    } else {
                        $data->setTime(0, 0, 0);
                        $save = true;
                        foreach ($olds as $old) {
                            $old->getDataOra()->setTime(0, 0, 0);
                            if ($data->getTimestamp() == $old->getDataOra()->getTimestamp()) {
                                $save = false;
                            }
                        }
                        if ($save) {
                            $em->persist($evento);
                            $em->flush();
                        }
                    }

                    $conn->commit();
                } catch (\Exception $e) {
                    $conn->rollback();
                    throw $e;
                }
            }
        }
        return array('entity' => $em->getRepository('EphpSinistriBundle:Scheda')->find($id));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-import-drive-default-{tipo}", name="tabellone_import_drive_default", defaults={"_format"="json", "tipo"="default"})
     * @Route("-import-drive-piemonte-{tipo}", name="tabellone_import_drive_piemonte", defaults={"_format"="json", "tipo"="piemonte"})
     */
    public function importAction($tipo) {
        $colonne = array();
        switch ($tipo) {
            case 'piemonte':
                $colonne = array('id', 'gestore', 'dasc', 'tpa', 'claimant', 'soi', 'first reserve', 'franchigia', 'amount reserved', 'stato', 'sa', 'offerta ns', 'offerta loro', 'priorita', 'recupero offerta ns', 'recupero offerta loro', 'claimant 2', 'gmail',);
                break;
            default:
                $colonne = array('id', 'gestore', 'dasc', 'tpa', 'claimant', 'soi', 'first reserve', 'amount reserved', 'stato', 'sa', 'offerta ns', 'offerta loro', 'priorita', 'recupero offerta ns', 'recupero offerta loro', 'claimant 2', 'gmail',);
                break;
        }
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
                                } elseif (count($tpa) == 2) {
                                    $tpa2 = explode('-', $tpa[0]);
                                    if (count($tpa2) != 2) {
                                        Funzioni::pr($dati);
                                        break(3);
                                    }
                                    $ospedale = $_ospedale->findOneBy(array('sigla' => $tpa2[0]));
                                    if (!$ospedale) {
                                        $ospedale = new Ospedale();
                                        $ospedale->setSigla($tpa2[0]);
                                        $ospedale->setNome($tpa2[0]);
                                        $ospedale->setGruppo('Piemonte');
                                        $ospedale->setNomeGruppo('Ospedali Piemonte');
                                        $em->persist($ospedale);
                                        $em->flush();
                                    }
                                    $anno = $tpa2[1];
                                    $tpa = $tpa[1];
                                    $scheda->setOspedale($ospedale);
                                    $scheda->setAnno($anno);
                                    $scheda->setTpa($tpa);
                                } else {
                                    Funzioni::pr($dati);
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
                                    $scheda->setFirstReserve($this->currency($dati[$index]));
                                }
                                break;
                            case 'franchigia':
                                $scheda->setFranchigia($this->currency($dati[$index]));
                                break;
                            case 'amount reserved':
                                if ($dati[$index]) {
                                    $scheda->setAmountReserved($this->currency($dati[$index]));
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
                                    $scheda->setSa($this->currency($dati[$index]));
                                }
                                break;
                            case 'offerta ns':
                                if ($dati[$index]) {
                                    $scheda->setOffertaNostra($this->currency($dati[$index]));
                                }
                                break;
                            case 'offerta loro':
                                if ($dati[$index]) {
                                    $scheda->setOffertaLoro($this->currency($dati[$index]));
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
                                    $scheda->setRecuperoOffertaNostra($this->currency($dati[$index]));
                                }
                                break;
                            case 'recupero offerta loro':
                                if ($dati[$index]) {
                                    $scheda->setRecuperoOffertaLoro($this->currency($dati[$index]));
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

    /**
     * Lists all Scheda entities.
     *
     * @Route("-import-xls-default-{tipo}", name="tabellone_import_xls_default", defaults={"_format"="json", "tipo"="default"})
     * @Route("-import-xls-piemonte-{tipo}", name="tabellone_import_xls_piemonte", defaults={"_format"="json", "tipo"="piemonte"})
     */
    public function importXlsAction($tipo) {
        $colonne = array();
        $em = $this->getEm();
        
        $_scheda = $em->getRepository('EphpSinistriBundle:Scheda');
        $_gestore = $em->getRepository('EphpACLBundle:Gestore');
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $_stato = $em->getRepository('EphpSinistriBundle:Stato');
        $_priorita = $em->getRepository('EphpSinistriBundle:Priorita');
        
        $schede_aggiunte = $schede_aggiornate = 0;

        $uri = __DIR__ . '/../../../../../web' . str_replace(' ', '+', urldecode($this->getRequest()->get('file')));
        set_time_limit(3600);
        switch ($tipo) {
            case 'piemonte':
                $colonne = array(
                    'ID',
                    'TDA Ref.',
                    'HOSPITAL',
                    'CLAIMANT',
                    'DOL',
                    'DON',
                    'TYPE OF LOSS',
                    'FIRST RESERVE INDICATION',
                    'APPLICABLE DEDUCTIBLE',
                    'AMOUNT RESERVED',
                    'DEDUCTIBLE RESERVED',
                    'LT FEES RESERVE',
                    'PROFESS. FEES RESERVE',
                    'POSSIBLE RECOVERY',
                    'AMOUNT SETTLED',
                    'DEDUC. PAID',
                    'PROFESS. FEES PAID',
                    'LT FEES PAID',
                    'TOTAL PAID',
                    'RECOVERED',
                    'TOTAL INCURRED',
                    'S.P.',
                    'M.P.L.',
                    'S. OF I.',
                    'STATUS',
                    'COMMENTS',
                );
                $doc = new \DOMDocument();
                $doc->loadHTMLFile($uri);
                $tag_html = $doc->getElementsByTagName('html')->item(0);
                $tag_body = $this->getDOMElement($tag_html, array('tag' => 'body'));
                $table = $this->getDOMElement($tag_body, array('tag' => 'table'));
                $trs = $this->getDOMElement($table, array('tag' => 'tr'), false);
                foreach ($trs as $tr) {
                    $tds = $this->getDOMElement($tr, array('tag' => 'td'), false);
                    if (count($tds) > 0) {
                        try {
                            $em->beginTransaction();
                            $scheda = new Scheda();
                            foreach ($tds as $idx => $td) {
                                switch ($colonne[$idx]) {
                                    case 'TDA Ref.':
                                        $tpa = explode('/', $td->nodeValue);
                                        if (count($tpa) == 3) {
                                            $ospedale = $_ospedale->findOneBy(array('sigla' => $tpa[0]));
                                            $anno = $tpa[1];
                                            $tpa = $tpa[2];
                                            $scheda->setOspedale($ospedale);
                                            $scheda->setAnno($anno);
                                            $scheda->setTpa($tpa);
                                        } elseif (count($tpa) == 2) {
                                            $tpa2 = explode('-', $tpa[0]);
                                            if (count($tpa2) != 2) {
                                                break(3);
                                            }
                                            $ospedale = $_ospedale->findOneBy(array('sigla' => $tpa2[0]));
                                            if (!$ospedale) {
                                                $ospedale = new Ospedale();
                                                $ospedale->setSigla($tpa2[0]);
                                                $ospedale->setNome($tpa2[0]);
                                                $ospedale->setGruppo('Piemonte');
                                                $ospedale->setNomeGruppo('Ospedali Piemonte');
                                                $em->persist($ospedale);
                                                $em->flush();
                                            }
                                            $anno = $tpa2[1];
                                            $tpa = $tpa[1];
                                            $scheda->setOspedale($ospedale);
                                            $scheda->setAnno($anno);
                                            $scheda->setTpa($tpa);
                                        } else {
                                            break(3);
                                        }
                                        break;

                                    case 'CLAIMANT':
                                        $scheda->setClaimant($td->nodeValue);
                                        break;
                                    case 'S. OF I.':
                                        if ($td->nodeValue) {
                                            $scheda->setSoi($td->nodeValue);
                                        }
                                        break;
                                    case 'FIRST RESERVE INDICATION':
                                        if ($td->nodeValue) {
                                            $scheda->setFirstReserve($this->currency($td->nodeValue));
                                        }
                                        break;
                                    case 'APPLICABLE DEDUCTIBLE':
                                        $scheda->setFranchigia($this->currency($td->nodeValue));
                                        break;
                                    case 'AMOUNT RESERVED':
                                        if ($td->nodeValue) {
                                            if($td->nodeValue != 'NP') {
                                                $scheda->setAmountReserved($this->currency($td->nodeValue));
                                            } else {
                                                $scheda->setAmountReserved(-1);
                                            }
                                        }
                                        break;
                                    case 'STATUS':
                                        if ($td->nodeValue) {
                                            $stato = $_stato->findOneBy(array('stato' => $td->nodeValue));
                                            if (!$stato) {
                                                $stato = new \Ephp\Bundle\SinistriBundle\Entity\Stato();
                                                $stato->setStato($td->nodeValue);
                                                $em->persist($stato);
                                                $em->flush();
                                            }
                                            $scheda->setStato($stato);
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
                            $em->commit();
                        } catch (\Exception $e) {
                            $em->rollback();
                            throw $e;
                        }
                    }
                }
                break;
            default:
                $colonne = array('id', 'gestore', 'dasc', 'tpa', 'claimant', 'soi', 'first reserve', 'amount reserved', 'stato', 'sa', 'offerta ns', 'offerta loro', 'priorita', 'recupero offerta ns', 'recupero offerta loro', 'claimant 2', 'gmail',);
                $data = new SpreadsheetExcelReader($uri);
                Funzioni::vd($data->dump(true, true));
                break;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('schede_aggiunte' => $schede_aggiunte, 'schede_aggiornate' => $schede_aggiornate)));
    }

    private function currency($euro) {
        return str_replace(array('€', '.', ',', ' '), array('', '', '.', ''), $euro);
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
            $_tipo->createTipo('ASC', 'Analisi Sinistri e Copertura', 'aaffaa', $cal);
            $_tipo->createTipo('VIM', 'Verifica Incarichi e Medici', 'aaffaa', $cal);
            $_tipo->createTipo('RPM', 'Ricerca Polizze e Medici', 'aaffaa', $cal);
            $_tipo->createTipo('RER', 'Relazione e Riserva', 'aaffaa', $cal);
            $_tipo->createTipo('RSA', 'Richiesta di SA', 'aaffaa', $cal);
            $_tipo->createTipo('TAX', 'Trattative e Aggiornamenti', 'aaffaa', $cal);
            $_tipo->createTipo('JWB', 'J-Web Claims', 'ffaaaa', $cal);
            $_tipo->createTipo('CNT', 'Cancelleria Telematiche', 'aaffff', $cal);
            $_tipo->createTipo('RVP', 'Ravinale Piemonte', 'ffaaff', $cal);
            $_tipo->createTipo('OTH', 'Attività manuali', 'ffffaa', $cal);
            $_tipo->createTipo('RIS', 'Rischedulazione', 'aaaaaa', $cal);
        }
        return $cal;
    }

    /**
     * 
     * @param \DOMElement $element
     * @param array $criteria
     * @param boolean $first
     * @return \BringOut\Bundle\GrabBundle\Controller\DOMElement
     */
    protected function getDOMElement(\DOMElement $element, $criteria = array(), $first = true) {
        $out = $first ? false : array();
        $i = 0;
        if (isset($criteria['vd'])) {
            Funzioni::pr('========================', true);
            Funzioni::pr($criteria, true);
        }
        if (isset($criteria['n'])) {
            $first = false;
            $out = false;
        }
        foreach ($element->childNodes as $tag) {
            if ($tag instanceof \DOMElement) {
                /* @var $tag \DOMElement */
                if (isset($criteria['vd'])) {
                    Funzioni::pr('------------------------', true);
                    $attributes = array();
                    foreach ($tag->attributes as $attr) {
                        /* @var $attr \DOMAttr */
                        $attributes[$attr->name] = $attr->value;
                    }
                    $vd = array(
                        'tag' => $tag->nodeName,
                        'attr' => $attributes,
                    );
                    if (isset($criteria['vd-value'])) {
                        $vd['value'] = $tag->nodeValue;
                    }
                    Funzioni::vd($vd, true);
                }
                if (isset($criteria['id'])) {
                    if ($tag->hasAttribute('id')) {
                        if ($tag->getAttribute('id') == $criteria['id']) {
                            if (isset($criteria['vd'])) {
                                Funzioni::pr('      \'RETURN\' => 1', true);
                            }
                            return $tag;
                        }
                    }
                }
                if (isset($criteria['tag'])) {
                    if ($tag->nodeName == $criteria['tag']) {
                        if ($first) {
                            if (isset($criteria['vd'])) {
                                Funzioni::pr('      \'RETURN\' => 1', true);
                            }
                            return $tag;
                        } elseif (isset($criteria['n'])) {
                            $i++;
                            if ($criteria['n'] == $i) {
                                if (isset($criteria['vd'])) {
                                    Funzioni::pr('      \'RETURN\' => 1', true);
                                }
                                return $tag;
                            }
                        } else {
                            if (isset($criteria['vd'])) {
                                Funzioni::pr('      \'RETURN\' => 1', true);
                            }
                            $out[] = $tag;
                        }
                    }
                }
                if (isset($criteria['class'])) {
                    if ($tag->hasAttribute('class')) {
                        if (strpos($tag->getAttribute('class'), $criteria['class']) !== false) {
                            if ($first) {
                                if (isset($criteria['vd'])) {
                                    Funzioni::pr('      \'RETURN\' => 1', true);
                                }
                                return $tag;
                            } elseif (isset($criteria['n'])) {
                                $i++;
                                if ($criteria['n'] == $i) {
                                    if (isset($criteria['vd'])) {
                                        Funzioni::pr('      \'RETURN\' => 1', true);
                                    }
                                    return $tag;
                                }
                            } else {
                                if (isset($criteria['vd'])) {
                                    Funzioni::pr('      \'RETURN\' => 1', true);
                                }
                                $out[] = $tag;
                            }
                        }
                    }
                }
            }
        }
        return $out;
    }

}

