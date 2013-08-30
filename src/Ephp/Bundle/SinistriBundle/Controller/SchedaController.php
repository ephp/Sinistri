<?php

namespace Ephp\Bundle\SinistriBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ephp\Bundle\ACLBundle\Entity\Gestore;
use Ephp\Bundle\CalendarBundle\Entity\Calendario;
use Ephp\Bundle\SinistriBundle\Entity\Evento;
use Ephp\Bundle\SinistriBundle\Entity\Link;
use Ephp\Bundle\SinistriBundle\Entity\Ospedale;
use Ephp\Bundle\SinistriBundle\Entity\Priorita;
use Ephp\Bundle\SinistriBundle\Entity\Scheda;
use Ephp\Bundle\SinistriBundle\Form\SchedaType;
use Ephp\Bundle\WsInvokerBundle\PhpExcel\SpreadsheetExcelReader;
use Ephp\UtilityBundle\Utility\Debug;
use Ephp\UtilityBundle\Utility\Dom;
use Ephp\UtilityBundle\Utility\String;
use Ephp\UtilityBundle\Utility\Time;

/**
 * Scheda controller.
 *
 * @Route("/tabellone")
 */
class SchedaController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController,
        \Ephp\Bundle\DragDropBundle\Controller\Traits\DragDropController,
        \Ephp\Bundle\SinistriBundle\Controller\Traits\SxCalendarController;

    /**
     * Lists all Scheda entities.
     *
     * @Route("/{ospedale}/{anno}", name="tabellone", defaults={"ospedale"="", "anno"=""})
     * @Template()
     */
    public function indexAction($ospedale, $anno) {
        $user = $this->getUser();
        /* @var $user \Ephp\Bundle\GestoriBundle\Entity\Gestore */
        if (!$user->hasRole('ROLE_ADMIN')) {
            return $this->redirect($this->generateUrl('tabellone_gestore', array('gestore' => $user->getSigla(), 'ospedale' => $ospedale, 'anno' => $anno)));
        }
        $em = $this->getEm();
        $mode = 0;
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        if ($ospedale && $anno) {
            $mode = 3;
            $ospedali = $_ospedale->findBy(array('gruppo' => $ospedale));
            $ospedali_id = array();
            foreach ($ospedali as $ospedale) {
                $ospedali_id[] = $ospedale->getId();
            }
            $entities = $this->findBy('EphpSinistriBundle:Scheda', array('ospedale' => $ospedali_id, 'anno' => $anno), array('anno' => 'ASC'), 200);
        } elseif ($ospedale) {
            $mode = 2;
            $ospedali = $_ospedale->findBy(array('gruppo' => $ospedale));
            $ospedali_id = array();
            foreach ($ospedali as $ospedale) {
                $ospedali_id[] = $ospedale->getId();
            }
            $entities = $this->findBy('EphpSinistriBundle:Scheda', array('ospedale' => $ospedali_id), array('anno' => 'DESC'), 200);
        } else {
            $mode = 1;
            $entities = $this->findBy('EphpSinistriBundle:Scheda', array(), array('anno' => 'DESC'), 200);
        }
        $ospedali = $_ospedale->findBy(array(), array('gruppo' => 'ASC'));
        $gestori = $this->findBy('EphpGestoriBundle:Gestore', array(), array('sigla' => 'ASC'));
        $priorita = $this->findBy('EphpSinistriBundle:Priorita', array());
        $stati_operativi = $this->findBy('EphpSinistriBundle:StatoOperativo', array());
        return array(
            'entities' => $entities,
            'mode' => $mode,
            'ospedale' => $ospedale,
            'anno' => $anno < 10 ? '0' . $anno : $anno,
            'ospedali' => $ospedali,
            'gestori' => $gestori,
            'priorita' => $priorita,
            'stati_operativi' => $stati_operativi,
            'anni' => range(7, date('y')),
            'scroll' => true,
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
        $gestore = $this->findOneBy('EphpGestoriBundle:Gestore', array('sigla' => $gestore));
        if ($ospedale && $anno) {
            $mode = 3;
            $ospedali = $_ospedale->findBy(array('gruppo' => $ospedale));
            $ospedali_id = array();
            foreach ($ospedali as $ospedale) {
                $ospedali_id[] = $ospedale->getId();
            }
            $entities = $this->findBy('EphpSinistriBundle:Scheda', array('gestore' => $gestore->getId(), 'ospedale' => $ospedali_id, 'anno' => $anno), array('anno' => 'DESC'), 200);
        } elseif ($ospedale) {
            $mode = 2;
            $ospedali = $_ospedale->findBy(array('gruppo' => $ospedale));
            $ospedali_id = array();
            foreach ($ospedali as $ospedale) {
                $ospedali_id[] = $ospedale->getId();
            }
            $entities = $this->findBy('EphpSinistriBundle:Scheda', array('gestore' => $gestore->getId(), 'ospedale' => $ospedali_id), array('anno' => 'DESC'), 200);
        } else {
            $mode = 1;
            $entities = $this->findBy('EphpSinistriBundle:Scheda', array('gestore' => $gestore->getId()), array('anno' => 'DESC'), 200);
        }
        $ospedali = $_ospedale->findBy(array(), array('gruppo' => 'ASC'));
        $gestori = $this->findBy('EphpGestoriBundle:Gestore', array(), array('sigla' => 'ASC'));
        $priorita = $this->findBy('EphpSinistriBundle:Priorita', array());
        $stati_operativi = $this->findBy('EphpSinistriBundle:StatoOperativo', array());
        return array(
            'entities' => $entities,
            'mode' => $mode,
            'ospedale' => $ospedale,
            'anno' => $anno < 10 ? '0' . $anno : $anno,
            'gestore' => $gestore,
            'ospedali' => $ospedali,
            'gestori' => $gestori,
            'priorita' => $priorita,
            'stati_operativi' => $stati_operativi,
            'anni' => range(7, date('y')),
            'scroll' => true,
        );
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-form-ricerca-avanzata/{gestore}/{ospedale}/{anno}", name="tabellone_form_ricerca", defaults={"gestore"="TUTTI", "ospedale"="TUTTI", "anno"="TUTTI"})
     * @Template()
     */
    public function formRicercaAction($gestore, $ospedale, $anno) {
        $scheda = new Scheda();
        $form = $this->createForm(new SchedaType(), $scheda);
        return array(
            'plain_gestore' => $gestore,
            'plain_ospedale' => $ospedale,
            'plain_anno' => $anno,
            'form' => $form->createView(),
        );
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-ricerca-avanzata/{gestore}/{ospedale}/{anno}", name="tabellone_ricerca", defaults={"gestore"="TUTTI", "ospedale"="TUTTI", "anno"="TUTTI"})
     * @Template("EphpSinistriBundle:Scheda:index.html.twig")
     */
    public function ricercaAction($gestore, $ospedale, $anno) {
        $em = $this->getEm();
        $mode = 0;
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $__gestore = $gestore;
        $__ospedale = $ospedale;
        $__anno = $anno;
        if ($gestore != 'TUTTI') {
            $gestore = $this->findOneBy('EphpGestoriBundle:Gestore', array('sigla' => $gestore));
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
        $scheda = new Scheda();
        $form = $this->createForm(new SchedaType(), $scheda);
        $form->bind($this->getRequest());
        if ($form->isValid()) {
            $entities = $em->getRepository('EphpSinistriBundle:Scheda')->cerca($gestore, $ospedali_id, $anno, $scheda);
        } else {
            $entities = array();
        }
        $ospedali = $_ospedale->findBy(array(), array('gruppo' => 'ASC'));
        $gestori = $this->findBy('EphpGestoriBundle:Gestore', array(), array('sigla' => 'ASC'));
        $priorita = $this->findBy('EphpSinistriBundle:Priorita', array());
        $stati_operativi = $this->findBy('EphpSinistriBundle:StatoOperativo', array());
        return array(
            'entities' => $entities,
            'mode' => $mode,
            'ospedale' => $ospedale,
            'anno' => $anno < 10 ? '0' . $anno : $anno,
            'gestore' => $gestore,
            'ospedali' => $ospedali,
            'gestori' => $gestori,
            'priorita' => $priorita,
            'stati_operativi' => $stati_operativi,
            'anni' => range(7, date('y')),
            'plain_gestore' => $__gestore,
            'plain_ospedale' => $__ospedale,
            'plain_anno' => $__anno,
            'form' => $form->createView(),
            'scroll' => false,
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
        if ($gestore != 'TUTTI') {
            $gestore = $this->findOneBy('EphpGestoriBundle:Gestore', array('sigla' => $gestore));
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
        $gestori = $this->findBy('EphpGestoriBundle:Gestore', array(), array('sigla' => 'ASC'));
        $priorita = $this->findBy('EphpSinistriBundle:Priorita', array());
        $stati_operativi = $this->findBy('EphpSinistriBundle:StatoOperativo', array());
        return array(
            'entities' => $entities,
            'mode' => $mode,
            'ospedale' => $ospedale,
            'anno' => $anno < 10 ? '0' . $anno : $anno,
            'gestore' => $gestore,
            'ospedali' => $ospedali,
            'gestori' => $gestori,
            'priorita' => $priorita,
            'stati_operativi' => $stati_operativi,
            'anni' => range(7, date('y')),
            'q' => $q,
            'scroll' => false,
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
        $_gestore = $em->getRepository('EphpGestoriBundle:Gestore');
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
        $entities = $em->getRepository('EphpSinistriBundle:Scheda')->findBy($params, array('anno' => 'DESC'), 200, 200 * ($pagina - 1));
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
     * @Route("-report/{id}", name="tabellone_report")
     * @Template()
     */
    public function reportAction($id) {
        $em = $this->getEm();

        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);
        /* @var $entity Scheda */

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }

        if (!$entity->getReportGestore()) {
            $entity->setReportAmountReserved($entity->getAmountReserved());
            $entity->setReportApplicableDeductable($entity->getApplicableDeductable());
            $entity->setReportDol($entity->getDol());
            $entity->setReportDon($entity->getDon());
            $entity->setReportGestore($entity->getGestore());
            $entity->setReportPossibleRecovery($entity->getPossibleRecovery());
            $entity->setReportServiceProvider($entity->getServiceProvider());
            $entity->setReportSoi($entity->getSoi());
            $entity->setReportTypeOfLoss($entity->getTypeOfLoss());
            $em->persist($entity);
            $em->flush();
        }

        return array(
            'entity' => $entity,
            'gestori' => $em->getRepository('EphpGestoriBundle:Gestore')->findBy(array(), array('nome' => 'ASC')),
        );
    }

    /**
     * Finds and displays a Scheda entity.
     *
     * @Route("-stampa/{id}", name="tabellone_stampa")
     * @Template()
     */
    public function stampaAction($id) {
        return $this->showAction($id);
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
                    array('tipo' => $this->ANALISI_SINISTRI_COPERTURA, 'giorni' => 0),
                    array('tipo' => $this->VERIFICA_INCARICHI_MEDICI,  'giorni' => 10),
                    array('tipo' => $this->RICERCA_POLIZZE_MEDICI,     'giorni' => 25),
                    array('tipo' => $this->RELAZIONE_RISERVA,          'giorni' => 14),
                    array('tipo' => $this->RICHIESTA_SA,               'giorni' => 14),
                    array('tipo' => $this->TRATTATIVE_AGGIORNAMENTI,   'giorni' => 30),
                    array('tipo' => $this->TRATTATIVE_AGGIORNAMENTI,   'giorni' => 14),
                    array('tipo' => $this->TRATTATIVE_AGGIORNAMENTI,   'giorni' => 14)
                );
                if (!$entity->getDasc()) {
                    $entity->setDasc(new \DateTime());
                    $em->persist($entity);
                    $em->flush();
                }
                $data = $entity->getDasc();
                foreach ($generatore as $i => $gen) {
                    $data = Time::calcolaData($data, $gen['giorni']);
                    $evento = $this->newEvento($gen['tipo'], $entity);
                    $evento->setDataOra($data);
                    $evento->setDeltaG($gen['giorni']);
                    $evento->setImportante(true);
                    $evento->setOrdine($i + 1);
                    $evento->setRischedulazione(true);
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
        $_priorita = $em->getRepository('EphpSinistriBundle:Priorita');
        $_gestore = $em->getRepository('EphpGestoriBundle:Gestore');

        $scheda = $_scheda->find($req['id']);
        /* @var $scheda Scheda */
        $gestore = $_gestore->findOneBy(array('sigla' => $req['gestore']));
        /* @var $gestore Gestore */

        $genera = is_null($scheda->getGestore());
        try {
            $scheda->setGestore($gestore);
            $scheda->setPriorita($_priorita->findOneBy(array('priorita' => 'assegnato')));
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

        $scheda = $this->find('EphpSinistriBundle:Scheda', $req['id']);
        /* @var $scheda Scheda */
        $priorita = $this->find('EphpSinistriBundle:Priorita', $req['priorita']);
        /* @var $priorita Priorita */

        try {
            $this->getEm()->beginTransaction();
            if($priorita->getOnChange() == 'cal') {
                $evento = $this->newEvento($this->PRIORITA, $scheda, $priorita->getPriorita(), "Cambio priorità da {$scheda->getPriorita()->getPriorita()} a {$priorita->getPriorita()}");
                $this->persist($evento);
            }
            $scheda->setPriorita($priorita);
            $this->persist($scheda);
            $this->getEm()->commit();
        } catch (\Exception $e) {
            $this->getEm()->rollback();
            throw $e;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('id' => $priorita->getId(), 'label' => $priorita->getPriorita(), 'css' => 'bg-' . $priorita->getCss(), 'js' => '')));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-cambia-stato-operativo-scheda", name="tabellone_cambia_stato_operativo", defaults={"_format"="json"})
     */
    public function cambiaStatoOperativoAction() {
        $req = $this->getRequest()->get('stato_operativo');
        $em = $this->getEm();

        $_scheda = $em->getRepository('EphpSinistriBundle:Scheda');
        $_priorita = $em->getRepository('EphpSinistriBundle:StatoOperativo');

        $scheda = $_scheda->find($req['id']);
        /* @var $scheda Scheda */
        $stato = $_priorita->find($req['stato']);
        /* @var $priorita Priorita */

        try {
            $old = $scheda->getStatoOperativo();
            $scheda->setStatoOperativo($stato);
            $em->persist($scheda);
            $em->flush();
            $evento = $this->newEvento($this->CAMBIO_STATO_OPERATIVO, $scheda, null, 'Da "' . $old->getStato() . '" a "' . $stato->getStato() . '"');
            $evento->setImportante(true);
            $em->persist($evento);
            $em->flush();
        } catch (\Exception $e) {
            throw $e;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('id' => $stato->getId(), 'label' => $stato->getStato())));
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
        /* @var $scheda \Ephp\Bundle\SinistriBundle\Entity\Scheda */
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
                case 'dati':
                    $scheda->setDati($req['value']);
                    break;
                case 'legali_avversari':
                    $scheda->setLegaliAvversari($req['value']);
                    break;
                case '_soi':
                    $scheda->setReportSoi($req['value']);
                    break;
                case '_dol':
                    $dol = \DateTime::createFromFormat('d/m/Y', $req['value']);
                    $scheda->setReportDol($dol);
                    break;
                case '_don':
                    $don = \DateTime::createFromFormat('d/m/Y', $req['value']);
                    $scheda->setReportDon($don);
                    break;
                case '_description':
                    $scheda->setReportDescrizione($req['value']);
                    break;
                case '_mpl':
                    $scheda->setReportMpl($req['value']);
                    break;
                case '_giudiziale':
                    $scheda->setReportGiudiziale($req['value']);
                    break;
                case '_other_policies':
                    $scheda->setReportOtherPolicies($req['value']);
                    break;
                case '_tpl':
                    $scheda->setReportTypeOfLoss($req['value']);
                    break;
                case '_possible_recovery':
                    $scheda->setReportPossibleRecovery($req['value']);
                    break;
                case '_amount_reserved':
                    $scheda->setReportAmountReserved($req['value']);
                    break;
                case '_applicable_deductable':
                    $scheda->setReportApplicableDeductable($req['value']);
                    break;
                case '_future_conduct':
                    $scheda->setReportFutureConduct($req['value']);
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
                    $req['reload'] = 1;
                    if ($req['value'] == '') {
                        $em->remove($evento);
                        $em->flush();
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
                                $data = Time::calcolaData($data, $gen['giorni']);
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
                                    $eventoR = $this->newEvento($this->RISCHEDULAZIONE, $evento->getScheda(), 'Rischedulazione', "{$evento->getTipo()->getNome()} (da " . date('d-m-Y', $old_data->getTimestamp()) . " a " . date('d-m-Y', $data->getTimestamp()) . ")");
                                    $eventoR->setOrdine($i + 1);
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
     * @Route("-attivita-cron", name="tabellone_attivita_cron", defaults={"_format"="json"})
     */
    public function attivitaCronAction() {
        $i = 0;
        $oggi = new \DateTime();
        $oggi->setTime(5, 0, 0);
        $em = $this->getEm();
        $cal = $this->getCalendar();
        $verifiche = 0;
        $tipo = $this->findOneBy('EphpCalendarBundle:Tipo', array('sigla' => 'VER', 'calendario' => $cal->getId()));
        do {
            $schede = $em->getRepository('EphpSinistriBundle:Scheda')->findBy(array(), array(), 200, $i * 200);
            foreach ($schede as $scheda) {
                set_time_limit(300);
                /* @var $scheda Scheda */
                $dasc = $scheda->getDasc();
                $ev = $em->getRepository('EphpSinistriBundle:Evento')->findOneBy(array('scheda' => $scheda->getId()));
                if ($dasc && $ev && $scheda->getPriorita() && $scheda->getPriorita()->getPriorita() != 'definita') {
                    $verifica = $em->getRepository('EphpSinistriBundle:Evento')->findOneBy(array('scheda' => $scheda->getId(), 'tipo' => $tipo->getId()), array('delta_g' => 'DESC'));
                    /* @var $verifica Evento */
                    $delta_g = 0;
                    if ($verifica) {
                        $delta_g = $verifica->getDeltaG();
                    }
                    $dasc->setTime(3, 0, 0);
                    $delta_g += 30;
                    $dasc = \Ephp\UtilityBundle\Utility\Time::calcolaData($dasc, $delta_g);
                    while ($oggi->getTimestamp() > $dasc->getTimestamp()) {
                        $eventoVerifica = $this->newEvento($this->VERIFICA_PERIODICA, $scheda, 'Verifica');
                        $eventoVerifica->setDataOra($dasc);
                        $eventoVerifica->setDeltaG($delta_g);
                        $eventoVerifica->setImportante(true);
                        $eventoVerifica->setOrdine($delta_g / 30);
                        $em->persist($eventoVerifica);
                        $em->flush();
                        $verifiche++;

                        $delta_g += 30;
                        $dasc = \Ephp\UtilityBundle\Utility\Time::calcolaData($dasc, 30);
                    };
                }
            }
            $i++;
        } while (count($schede) > 0);
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('verifiche' => $verifiche)));
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
     * @Route("-uploadJs", name="tabellone_upload_js")
     * @Template("EphpDragDropBundle:DragDrop:js.html.php")
     */
    public function uploadJsAction() {
        return array();
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
     * @Route("-cancella-link", name="tabellone_cancella_link")
     * @Template("EphpSinistriBundle:Scheda:show/link.html.twig")
     */
    public function cancellaLinkAction() {
        $req = $this->getRequest()->get('link');
        $em = $this->getEm();
        $entity = $em->getRepository('EphpSinistriBundle:Link')->find($req['id']);
        /* @var $entity Link */
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }
        $id = $entity->getScheda()->getId();
        $em->remove($entity);
        $em->flush();
        return array('entity' => $em->getRepository('EphpSinistriBundle:Scheda')->find($id));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-cancella-evento", name="tabellone_cancella_evento")
     * @Template("EphpSinistriBundle:Scheda:show/tabella.html.twig")
     */
    public function cancellaEventoAction() {
        $req = $this->getRequest()->get('evento');
        $em = $this->getEm();
        $entity = $em->getRepository('EphpSinistriBundle:Evento')->find($req['id']);
        /* @var $entity Evento */
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }
        $id = $entity->getScheda()->getId();
        $em->remove($entity);
        $em->flush();
        return array('entity' => $em->getRepository('EphpSinistriBundle:Scheda')->find($id));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-primo-piano", name="tabellone_prima_pagina", defaults={"_format"="json"})
     */
    public function primoPianoAction() {
        $req = $this->getRequest()->get('scheda');
        $em = $this->getEm();
        $scheda = $em->getRepository('EphpSinistriBundle:Scheda')->find($req['id']);
        /* @var $evento Scheda */
        if (!$scheda) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }
        $out = array('id' => 'star_' . $req['id'], 'remove' => $scheda->getPrimaPagina() ? 'cal_important' : 'cal_normal', 'add' => $scheda->getPrimaPagina() ? 'cal_normal' : 'cal_important');
        $scheda->setPrimaPagina(!$scheda->getPrimaPagina());
        $em->persist($scheda);
        $em->flush();
        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-evidenzia-evento", name="tabellone_evidenzia_evento", defaults={"_format"="json"})
     */
    public function evidenziaEventoAction() {
        $req = $this->getRequest()->get('evento');
        $em = $this->getEm();
        $evento = $em->getRepository('EphpSinistriBundle:Evento')->find($req['id']);
        /* @var $evento Evento */
        if (!$evento) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }
        $out = array('id' => 'star_' . $req['id'], 'remove' => $evento->getImportante() ? 'cal_important' : 'cal_normal', 'add' => $evento->getImportante() ? 'cal_normal' : 'cal_important');
        $evento->setImportante(!$evento->getImportante());
        $em->persist($evento);
        $em->flush();
        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }

    /**
     * Lists all Scheda entities.
     *
     * @Route("-aggiungi-link/{id}", name="tabellone_aggiungi_link")
     * @Template("EphpSinistriBundle:Scheda:show/link.html.twig")
     */
    public function aggiungiLinkAction($id) {
        $req = $this->getRequest()->get('link');
        $em = $this->getEm();
        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->find($id);
        /* @var $entity Scheda */
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scheda entity.');
        }
        $link = new Link();
        $link->setScheda($entity);
        $link->setUrl($req['url']);
        $link->setSito($req['sito']);
        $em->persist($link);
        $em->flush();
        return array('entity' => $em->getRepository('EphpSinistriBundle:Scheda')->find($id));
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
        $evento = $this->newEvento($this->ATTIVITA_MANUALE, $entity, $req['titolo'], $req['note']);
        $evento->setDataOra($data);
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
//                    Debug::vd($old);
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
                    $evento = $this->newEvento($this->JWEB, $entity, $dati[2], $dati[3] . ($dati[1] ? "({$dati[1]})" : ''));
                    $evento->setDataOra($data);
                    $olds = $em->getRepository('EphpSinistriBundle:Evento')->findBy(array(
                        'calendario' => $cal->getId(),
                        'tipo' => $evento->getTipo()->getId(),
                        'scheda' => $entity->getId(),
                        'titolo' => $evento->getTitolo(),
                        'note' => $evento->getNote(),
                    ));
//                    Debug::vd($old);
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
                    $evento = $this->newEvento($this->CANCELLERIA_TELEMATICA, $entity, $dati[1], $dati[2]);
                    $evento->setDataOra($data);
                    $olds = $em->getRepository('EphpSinistriBundle:Evento')->findBy(array(
                        'calendario' => $cal->getId(),
                        'tipo' => $evento->getTipo()->getId(),
                        'scheda' => $entity->getId(),
                        'titolo' => $evento->getTitolo(),
                        'note' => $evento->getNote(),
                    ));
//                    Debug::vd($old);
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
                    $evento = $this->newEvento($this->RAVINALE, $entity, 'Ravinale Piemonte', $dati[1]);
                    $evento->setDataOra($data);
                    $olds = $em->getRepository('EphpSinistriBundle:Evento')->findBy(array(
                        'calendario' => $cal->getId(),
                        'tipo' => $evento->getTipo()->getId(),
                        'scheda' => $entity->getId(),
                        'note' => $evento->getNote(),
                    ));
//                    Debug::vd($old);
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
        $_gestore = $em->getRepository('EphpGestoriBundle:Gestore');
        $_ospedale = $em->getRepository('EphpSinistriBundle:Ospedale');
        $_stato = $em->getRepository('EphpSinistriBundle:Stato');
        $_priorita = $em->getRepository('EphpSinistriBundle:Priorita');

        if ($handle = fopen($uri, 'r')) {
            fgetcsv($handle, 0, ',');
            while ($dati = fgetcsv($handle, 0, ',')) {
                try {
                    $conn->beginTransaction();
                    $scheda = new Scheda();
                    $scheda->setPriorita($em->getRepository('EphpSinistriBundle:Priorita')->findOneBy(array('priorita' => 'nuovo')));
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
                                        Debug::pr($dati);
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
                                    Debug::pr($dati);
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
                                    $scheda->setFirstReserve(String::currency($dati[$index]));
                                }
                                break;
                            case 'franchigia':
                                $scheda->setFranchigia(String::currency($dati[$index]));
                                break;
                            case 'amount reserved':
                                if ($dati[$index]) {
                                    $scheda->setAmountReserved(String::currency($dati[$index]));
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
                                    $scheda->setSa(String::currency($dati[$index]));
                                }
                                break;
                            case 'offerta ns':
                                if ($dati[$index]) {
                                    $scheda->setOffertaNostra(String::currency($dati[$index]));
                                }
                                break;
                            case 'offerta loro':
                                if ($dati[$index]) {
                                    $scheda->setOffertaLoro(String::currency($dati[$index]));
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
                                    $scheda->setRecuperoOffertaNostra(String::currency($dati[$index]));
                                }
                                break;
                            case 'recupero offerta loro':
                                if ($dati[$index]) {
                                    $scheda->setRecuperoOffertaLoro(String::currency($dati[$index]));
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
        $_gestore = $em->getRepository('EphpGestoriBundle:Gestore');
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
                    'LT FEES PAID',
                    'PROFESS. FEES PAID',
                    'TOTAL PAID',
                    'RECOVERED',
                    'TOTAL INCURRED',
                    'S.P.',
                    'M.P.L.',
                    'S. OF I.',
                    'STATUS',
                    'COURT',
                    'COMMENTS',
                );
                $doc = new \DOMDocument();
                $doc->loadHTMLFile($uri);
                $tag_html = Dom::getDOMBase($doc);
                $tag_body = Dom::getDOMElement($tag_html, array('tag' => 'body'));
                $table = Dom::getDOMElement($tag_body, array('tag' => 'table'));
                $trs = Dom::getDOMElement($table, array('tag' => 'tr'), false);
                foreach ($trs as $tr) {
                    $tds = Dom::getDOMElement($tr, array('tag' => 'td'), false);
                    if (count($tds) > 0) {
                        try {
                            $em->beginTransaction();
                            $scheda = new Scheda();
                            $scheda->setPriorita($_priorita->findOneBy(array('priorita' => 'nuovo')));
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
                                                $ospedale->setTpa('Ravinale');
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
                                    case 'DOL':
                                        if ($td->nodeValue) {
                                            $dol = \DateTime::createFromFormat('d/m/Y', $td->nodeValue);
                                            $scheda->setDol($dol);
                                        }
                                        break;
                                    case 'DON':
                                        if ($td->nodeValue) {
                                            $don = \DateTime::createFromFormat('d/m/Y', $td->nodeValue);
                                            $scheda->setDon($don);
                                        }
                                        break;
                                    case 'TYPE OF LOSS':
                                        if ($td->nodeValue) {
                                            $scheda->setTypeOfLoss($td->nodeValue);
                                        }
                                        break;
                                    case 'FIRST RESERVE INDICATION':
                                        if ($td->nodeValue) {
                                            $scheda->setFirstReserve(String::currency($td->nodeValue));
                                        }
                                        break;
                                    case 'APPLICABLE DEDUCTIBLE':
                                        $scheda->setApplicableDeductable(String::currency($td->nodeValue));
                                        $scheda->setFranchigia(String::currency($td->nodeValue));
                                        break;
                                    case 'AMOUNT RESERVED':
                                        if ($td->nodeValue) {
                                            if ($td->nodeValue != 'NP') {
                                                $scheda->setAmountReserved(String::currency($td->nodeValue));
                                            } else {
                                                $scheda->setAmountReserved(-1);
                                            }
                                        }
                                        break;
                                    case 'POSSIBLE RECOVERY':
                                        $scheda->setPossibleRecovery(String::currency($td->nodeValue));
                                        break;
                                    case 'TYPE OF LOSS':
                                        if ($td->nodeValue) {
                                            $scheda->setServiceProvider($td->nodeValue);
                                        }
                                        break;
                                    case 'COURT':
                                        if ($td->nodeValue) {
                                            switch ($td->nodeValue) {
                                                case "Civil":
                                                    $scheda->setGiudiziale('Y');
                                                    $scheda->setGiudiziale('C');
                                                    break;
                                                case "Criminal":
                                                    $scheda->setGiudiziale('Y');
                                                    $scheda->setGiudiziale('J');
                                                    break;
                                                case "Civil and criminal":
                                                case "Criminal and civil":
                                                    $scheda->setGiudiziale('Y');
                                                    $scheda->setGiudiziale('A');
                                                    break;
                                                default:
                                                    $scheda->setGiudiziale(' ');
                                                    break;
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
                                if ($old->getPriorita() && $old->getPriorita()->getPriorita() == 'definita') {
                                    if ($old->getStato()->getId() != $scheda->getStato()->getId()) {
                                        $old->setPriorita($_priorita->findOneBy(array('priorita' => 'riattivato')));
                                    }
                                }
                                $old->setAmountReserved($scheda->getAmountReserved());
                                $old->setFirstReserve($scheda->getFirstReserve());
                                $old->setDol($scheda->getDol());
                                $old->setDon($scheda->getDon());
                                $old->setPossibleRecovery($scheda->getPossibleRecovery());
                                $old->setFranchigia($scheda->getFranchigia());
                                $old->setTypeOfLoss($scheda->getTypeOfLoss());
                                $old->setServiceProvider($scheda->getServiceProvider());
                                $old->setApplicableDeductable($scheda->getApplicableDeductable());
                                $old->setSoi($scheda->getSoi());
                                $old->setStato($scheda->getStato());
                                $old->setGiudiziale($scheda->getGiudiziale());
                                $em->persist($old);
                                $em->flush();
                                $schede_aggiornate++;
                            } else {
                                $scheda->setStatoOperativo($this->findOneBy('EphpSinistriBundle:StatoOperativo', array('primo' => true)));
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
                $data = new SpreadsheetExcelReader($uri);
                //return new \Symfony\Component\HttpFoundation\Response(json_encode($data->sheets));
                foreach ($data->sheets as $sheet) {
                    $sheet = $sheet['cells'];
                    $start = false;
                    $colonne = array();
                    foreach ($sheet as $riga => $valori_riga) {
                        if (!$start) {
                            if (count($colonne) > 0) {
                                $start = true;
                            }
                            if (isset($valori_riga[2]) && in_array($valori_riga[2], array('TPA  Ref.', 'TPA Ref.'))) {
                                $colonne = $valori_riga;
                            }
                        } else {
                            if (!isset($valori_riga[2]) || !$valori_riga[2]) {
                                break;
                            } else {
                                try {
                                    $em->beginTransaction();
                                    $scheda = new Scheda();
                                    $scheda->setPriorita($_priorita->findOneBy(array('priorita' => 'nuovo')));
                                    foreach ($valori_riga as $idx => $value) {
                                        if (!isset($colonne[$idx])) {
                                            break;
                                        }
                                        switch ($colonne[$idx]) {
                                            case 'TPA  Ref.':
                                            case 'TPA Ref.':
                                                $tpa = explode('/', $value);
                                                if (count($tpa) == 3) {
                                                    $ospedale = $_ospedale->findOneBy(array('sigla' => $tpa[0]));
                                                    if (!$ospedale) {
                                                        $ospedale = new Ospedale();
                                                        $ospedale->setSigla($tpa[0]);
                                                        $ospedale->setNome($sheet[6][14]);
                                                        $ospedale->setGruppo($tpa[0]);
                                                        $ospedale->setNomeGruppo($sheet[6][14]);
                                                        $ospedale->setTpa('Contec');
                                                        $em->persist($ospedale);
                                                        $em->flush();
                                                    }
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

                                            case 'CLAYMANT':
                                                $scheda->setClaimant($value);
                                                break;
                                            case 'COURT':
                                                $scheda->setGiudiziale($value);
                                                break;
                                            case 'DOL':
                                                if ($value) {
                                                    $dol = \DateTime::createFromFormat('d/m/Y', $value);
                                                    $scheda->setDol($dol);
                                                }
                                                break;
                                            case 'DON':
                                                if ($value) {
                                                    $don = \DateTime::createFromFormat('d/m/Y', $value);
                                                    $scheda->setDon($don);
                                                }
                                                break;
                                            case 'TYPE OF LOSS':
                                                if ($value) {
                                                    $scheda->setTypeOfLoss($value);
                                                }
                                                break;
                                            case 'S.P.':
                                                if ($value) {
                                                    $scheda->setServiceProvider($value);
                                                }
                                                break;
                                            case 'S.of I.':
                                                if ($value) {
                                                    $scheda->setSoi($value);
                                                }
                                                break;
                                            case 'LEGAL TEAM':
                                                if ($value) {
                                                    $dasc = \DateTime::createFromFormat('d/m/Y', $value);
                                                    $scheda->setDasc($dasc);
                                                } else {
                                                    $scheda->setDasc(null);
                                                }
                                                break;
                                            case 'FIRST RESERVE INDICATION':
                                                if ($value) {
                                                    $scheda->setFirstReserve(String::currency($value, ',', '.'));
                                                }
                                                break;
                                            case 'POSSIBLE RECOVERY':
                                                if ($value) {
                                                    $scheda->setPossibleRecovery(String::currency($value, ',', '.'));
                                                }
                                                break;
                                            case 'DEDUC. RESERVE':
                                                $scheda->setApplicableDeductable(String::currency($value, ',', '.'));
                                                $scheda->setFranchigia(String::currency($value, ',', '.'));
                                                break;
                                            case 'AMOUNT RESERVED':
                                                if ($value) {
                                                    if ($value != 'N.P.') {
                                                        $scheda->setAmountReserved(String::currency($value, ',', '.'));
                                                    } else {
                                                        $scheda->setAmountReserved(-1);
                                                    }
                                                }
                                                break;
                                            case 'STATUS':
                                                if ($value) {
                                                    $stato = $_stato->findOneBy(array('stato' => $value));
                                                    if (!$stato) {
                                                        $stato = new \Ephp\Bundle\SinistriBundle\Entity\Stato();
                                                        $stato->setStato($value);
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
//                                        Debug::vd($old, true);
                                        if ($old->getPriorita() && $old->getPriorita()->getPriorita() == 'definita') {
                                            if ($old->getStato()->getId() != $scheda->getStato()->getId()) {
                                                $old->setPriorita($_priorita->findOneBy(array('priorita' => 'riattivato')));
                                            }
                                        }
                                        $old->setAmountReserved($scheda->getAmountReserved());
                                        $old->setFirstReserve($scheda->getFirstReserve());
                                        $old->setDasc($scheda->getDasc());
                                        $old->setDol($scheda->getDol());
                                        $old->setDon($scheda->getDon());
                                        $old->setGiudiziale($scheda->getGiudiziale());
                                        $old->setPossibleRecovery($scheda->getPossibleRecovery());
                                        $old->setFranchigia($scheda->getFranchigia());
                                        $old->setTypeOfLoss($scheda->getTypeOfLoss());
                                        $old->setServiceProvider($scheda->getServiceProvider());
                                        $old->setApplicableDeductable($scheda->getApplicableDeductable());
                                        $old->setSoi($scheda->getSoi());
                                        $old->setStato($scheda->getStato());
//                                        Debug::vd($old);
                                        $em->persist($old);
                                        $em->flush();
                                        $schede_aggiornate++;
                                    } else {
                                        $scheda->setStatoOperativo($this->findOneBy('EphpSinistriBundle:StatoOperativo', array('primo' => true)));
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
                    }
                }
                break;
        }
        return new \Symfony\Component\HttpFoundation\Response(json_encode(array('schede_aggiunte' => $schede_aggiunte, 'schede_aggiornate' => $schede_aggiornate)));
    }

    private function currency($euro) {
        return str_replace(array('€', '.', ',', ' '), array('', '', '.', ''), $euro);
    }

}