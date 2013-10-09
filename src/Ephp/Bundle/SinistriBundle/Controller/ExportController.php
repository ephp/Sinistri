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
 * @Route("/export")
 */
class ExportController extends Controller {

    use \Ephp\UtilityBundle\Controller\Traits\BaseController,
        \Ephp\Bundle\DragDropBundle\Controller\Traits\DragDropController,
        \Ephp\Bundle\SinistriBundle\Controller\Traits\SxCalendarController;

    /**
     * Finds and displays a Scheda entity.
     *
     * @Route("-scheda/{ospedale}/{anno}/{tpa}/esporta-cron", name="export_scheda", defaults={"_format": "json"})
     * @Template()
     */
    public function showAction($ospedale, $anno, $tpa) {
        $em = $this->getEm();

        $ospedale = $em->getRepository('EphpSinistriBundle:Ospedale')->findOneBy(array('sigla' => $ospedale));
        $entity = $em->getRepository('EphpSinistriBundle:Scheda')->findOneBy(array('ospedale' => $ospedale->getId(), 'anno' => $anno, 'tpa' => $tpa));
        /* @var $entity Scheda */

        if (!$entity) {
            return new \Symfony\Component\HttpFoundation\Response(json_encode(array('error' => 'Scheda non trovata')));
        }
        
        $reports = array();
        foreach ($entity->getReports() as $report) {
            /* @var $report \Ephp\Bundle\SinistriBundle\Entity\Report */
            $reports[] = array(
                'analisi_danno' => $report->getAnalisiDanno(),
                'azioni' => $report->getAzioni(),
                'copertura' => $report->getCopertura(),
                'data' => $report->getData()->format('Y-m-d'),
                'descrizione_in_fatto' => $report->getDescrizioneInFatto(),
                'medico_legale1' => $report->getMedicoLegale1(),
                'medico_legale2' => $report->getMedicoLegale2(),
                'medico_legale3' => $report->getMedicoLegale3(),
                'note' => $report->getNote(),
                'number' => $report->getNumber(),
                'possibile_rivalsa' => $report->getPossibileRivalsa(),
                'relazione_avversaria' => $report->getRelazioneAvversaria(),
                'relazione_ex_adverso' => $report->getRelazioneExAdverso(),
                'richiesta_sa' => $report->getRichiestaSa(),
                'riserva' => $report->getRiserva(),
                'stato' => $report->getStato(),
                'validato' => $report->getValidato(),
                'valutazione_responsabilita' => $report->getValutazioneResponsabilita(),
            );
        }
        
        $links = array();
        foreach ($entity->getLinks() as $link) {
            /* @var $link \Ephp\Bundle\SinistriBundle\Entity\Link */
            $links[] = array(
                'sito' => $link->getSito(),
                'url' => $link->getUrl(),
            );
        }
        
        $eventi = array();
        foreach ($entity->getEventi() as $evento) {
            /* @var $evento \Ephp\Bundle\SinistriBundle\Entity\Evento */
            $eventi[] = array(
                'data_ora' => $evento->getDataOra()->format('Y-m-d'),
                'delta_g' => $evento->getDeltaG(),
                'giorno_intero' => $evento->getGiornoIntero(),
                'importante' => $evento->getImportante(),
                'note' => $evento->getNote(),
                'ordine' => $evento->getOrdine(),
                'rischedulazione' => $evento->getRischedulazione(),
                'tipo' => $evento->getTipo()->getSigla(),
                'titolo' => $evento->getTitolo(),
            );
        }
        
        $out = array(
            'dasc' => $entity->getDasc() ? $entity->getDasc()->format('Y-m-d') : null,
            'gestore' => $entity->getGestore() ? $entity->getGestore()->getSigla() : null,
            'priorita' => $entity->getPriorita() ? $entity->getPriorita()->getPriorita() : null,
            'stato' => $entity->getStatoOperativo() ? $entity->getStatoOperativo()->getStato() : null,
            'note' => $entity->getNote(),
            'avversari' => $entity->getLegaliAvversari(),
            'dati' => $entity->getDati(),
            'sa' => $entity->getSa(),
            'offerta_nostra' => $entity->getOffertaNostra(),
            'offerta_loro' => $entity->getOffertaLoro(),
            'recupero_offerta_nostra' => $entity->getRecuperoOffertaNostra(),
            'recupero_offerta_loro' => $entity->getRecuperoOffertaLoro(),
            'report' => array(
                'amount_reserved' => $entity->getReportAmountReserved(),
                'applicable_deductable' => $entity->getReportApplicableDeductable(),
                'descrizione' => $entity->getReportDescrizione(),
                'dol' => $entity->getReportDol() ? $entity->getReportDol()->format('Y-m-d') : null,
                'don' => $entity->getReportDon() ? $entity->getReportDon()->format('Y-m-d') : null,
                'future_conduct' => $entity->getReportFutureConduct(),
                'gestore' => $entity->getReportGestore() ? $entity->getReportGestore()->getSigla() : null,
                'giudiziale' => $entity->getReportGiudiziale(),
                'mpl' => $entity->getReportMpl(),
                'old' => $entity->getReportOld(),
                'other_policies' => $entity->getReportOtherPolicies(),
                'possible_recovery' => $entity->getReportPossibleRecovery(),
                'service_provider' => $entity->getReportServiceProvider(),
                'soi' => $entity->getReportSoi(),
                'type_of_loss' => $entity->getReportTypeOfLoss(),
                'reports' => $reports,
            ),
            'links' => $links,
            'eventi' => $eventi,
        );
        
        return new \Symfony\Component\HttpFoundation\Response(json_encode($out));
    }


}