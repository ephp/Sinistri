<?php

namespace Ephp\Bundle\SinistriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Scheda
 *
 * @ORM\Table(name="sx_tabellone")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\SinistriBundle\Entity\SchedaRepository")
 */
class Scheda {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Ospedale")
     * @ORM\JoinColumn(name="ospedale_id", referencedColumnName="id")
     */
    private $ospedale;

    /**
     * @var integer
     *
     * @ORM\Column(name="anno", type="integer")
     */
    private $anno;

    /**
     * @ORM\ManyToOne(targetEntity="\Ephp\Bundle\GestoriBundle\Entity\Gestore")
     * @ORM\JoinColumn(name="gestore_id", referencedColumnName="id")
     */
    private $gestore;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dasc", type="date", nullable=true)
     */
    private $dasc;

    /**
     * @var string
     *
     * @ORM\Column(name="tpa", type="integer")
     */
    private $tpa;

    /**
     * @var string
     *
     * @ORM\Column(name="claimant", type="string", length=255)
     */
    private $claimant;

    /**
     * @var string
     *
     * @ORM\Column(name="soi", type="string", length=2, nullable=true)
     */
    private $soi;

    /**
     * @var string
     *
     * @ORM\Column(name="giudiziale", type="string", length=1, nullable=true)
     */
    private $giudiziale;

    /**
     * @var float
     *
     * @ORM\Column(name="first_reserve", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $first_reserve;

    /**
     * @var float
     *
     * @ORM\Column(name="amount_reserved", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $amount_reserved;

    /**
     * @var float
     *
     * @ORM\Column(name="franchigia", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $franchigia;

    /**
     * @ORM\ManyToOne(targetEntity="Stato")
     * @ORM\JoinColumn(name="stato_id", referencedColumnName="id")
     */
    private $stato;

    /**
     * @var float
     *
     * @ORM\Column(name="sa", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $sa;

    /**
     * @var string
     *
     * @ORM\Column(name="offerta_nostra", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $offerta_nostra;

    /**
     * @var float
     *
     * @ORM\Column(name="offerta_loro", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $offerta_loro;

    /**
     * @var string
     *
     * @ORM\Column(name="recupero_offerta_nostra", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $recupero_offerta_nostra;

    /**
     * @var float
     *
     * @ORM\Column(name="recupero_offerta_loro", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $recupero_offerta_loro;

    /**
     * @ORM\ManyToOne(targetEntity="Priorita")
     * @ORM\JoinColumn(name="priorita_id", referencedColumnName="id")
     */
    private $priorita;

    /**
     * @var float
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var float
     *
     * @ORM\Column(name="legali_avversari", type="text", nullable=true)
     */
    private $legali_avversari;

    /**
     * @var float
     *
     * @ORM\Column(name="dati", type="text", nullable=true)
     */
    private $dati;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Evento", mappedBy="scheda", cascade={"persist", "remove", "merge", "refresh"}, orphanRemoval=true)
     * @ORM\OrderBy({"data_ora" = "ASC"})
     */
    private $eventi;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Report", mappedBy="scheda", cascade={"persist", "remove", "merge", "refresh"}, orphanRemoval=true)
     * @ORM\OrderBy({"number" = "DESC"})
     */
    private $reports;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Link", mappedBy="scheda", cascade={"persist", "remove", "merge", "refresh"}, orphanRemoval=true)
     * @ORM\OrderBy({"sito" = "ASC"})
     */
    private $links;

    /**
     * @var float
     *
     * @ORM\Column(name="prima_pagina", type="boolean", nullable=true)
     */
    private $prima_pagina;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dol", type="date", nullable=true)
     */
    private $dol;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="don", type="date", nullable=true)
     */
    private $don;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type_of_loss", type="string", length=5, nullable=true)
     */
    private $type_of_loss;
    
    /**
     * @var string
     *
     * @ORM\Column(name="service_provider", type="string", length=5, nullable=true)
     */
    private $service_provider;

    /**
     * @var float
     *
     * @ORM\Column(name="possible_recovery", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $possible_recovery;
    
    /**
     * @var float
     *
     * @ORM\Column(name="applicable_deductable", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $applicable_deductable;
    
    // REPORT
    
    /**
     * @var string
     *
     * @ORM\Column(name="report_soi", type="string", length=2, nullable=true)
     */
    private $report_soi;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="report_dol", type="date", nullable=true)
     */
    private $report_dol;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="report_don", type="date", nullable=true)
     */
    private $report_don;

    /**
     * @var float
     *
     * @ORM\Column(name="report_descrizione", type="text", nullable=true)
     */
    private $report_descrizione;

    /**
     * @var float
     *
     * @ORM\Column(name="report_mpl", type="text", nullable=true)
     */
    private $report_mpl;

    /**
     * @var float
     *
     * @ORM\Column(name="report_giudiziale", type="text", nullable=true)
     */
    private $report_giudiziale;

    /**
     * @var float
     *
     * @ORM\Column(name="report_other_policies", type="text", nullable=true)
     */
    private $report_other_policies;
    
    /**
     * @var string
     *
     * @ORM\Column(name="report_type_of_loss", type="string", length=5, nullable=true)
     */
    private $report_type_of_loss;
    
    /**
     * @var string
     *
     * @ORM\Column(name="report_service_provider", type="string", length=5, nullable=true)
     */
    private $report_service_provider;

    /**
     * @var float
     *
     * @ORM\Column(name="report_possible_recovery", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $report_possible_recovery;

    /**
     * @var float
     *
     * @ORM\Column(name="report_amount_reserved", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $report_amount_reserved;

    /**
     * @var float
     *
     * @ORM\Column(name="report_applicable_deductable", type="decimal", precision=15, scale=2, nullable=true)
     */
    private $report_applicable_deductable;

    /**
     * @var float
     *
     * @ORM\Column(name="report_future_conduct", type="text", nullable=true)
     */
    private $report_future_conduct;

    /**
     * @ORM\ManyToOne(targetEntity="\Ephp\Bundle\GestoriBundle\Entity\Gestore")
     * @ORM\JoinColumn(name="report_gestore_id", referencedColumnName="id")
     */
    private $report_gestore;

    /**
     * @var float
     *
     * @ORM\Column(name="report_old", type="text", nullable=true)
     */
    private $report_old;
    
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->eventi = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set anno
     *
     * @param integer $anno
     * @return Scheda
     */
    public function setAnno($anno) {
        $this->anno = $anno;

        return $this;
    }

    /**
     * Get anno
     *
     * @return integer
     */
    public function getAnno() {
        return $this->anno;
    }

    /**
     * Get anno a due cifre
     *
     * @return integer
     */
    public function getAnno2() {
        return $this->anno < 10 ? '0' . $this->anno : $this->anno;
    }

    /**
     * Set dasc
     *
     * @param \DateTime $dasc
     * @return Scheda
     */
    public function setDasc($dasc) {
        $this->dasc = $dasc;

        return $this;
    }

    /**
     * Get dasc
     *
     * @return \DateTime
     */
    public function getDasc() {
        return $this->dasc;
    }

    /**
     * Set tpa
     *
     * @param integer $tpa
     * @return Scheda
     */
    public function setTpa($tpa) {
        $this->tpa = $tpa;

        return $this;
    }

    /**
     * Get tpa
     *
     * @return integer
     */
    public function getTpa() {
        return $this->tpa;
    }

    /**
     * Set claimant
     *
     * @param string $claimant
     * @return Scheda
     */
    public function setClaimant($claimant) {
        $this->claimant = $claimant;

        return $this;
    }

    /**
     * Get claimant
     *
     * @return string
     */
    public function getClaimant() {
        return $this->claimant;
    }

    /**
     * Set soi
     *
     * @param string $soi
     * @return Scheda
     */
    public function setSoi($soi) {
        $this->soi = $soi;

        return $this;
    }

    /**
     * Get soi
     *
     * @return string
     */
    public function getSoi() {
        return $this->soi;
    }

    /**
     * Set first_reserve
     *
     * @param float $firstReserve
     * @return Scheda
     */
    public function setFirstReserve($firstReserve) {
        $this->first_reserve = $firstReserve;

        return $this;
    }

    /**
     * Get first_reserve
     *
     * @return float
     */
    public function getFirstReserve() {
        return $this->first_reserve;
    }

    /**
     * Set amount_reserved
     *
     * @param float $amountReserved
     * @return Scheda
     */
    public function setAmountReserved($amountReserved) {
        $this->amount_reserved = $amountReserved;

        return $this;
    }

    /**
     * Get amount_reserved
     *
     * @return float
     */
    public function getAmountReserved() {
        return $this->amount_reserved;
    }

    /**
     * Set sa
     *
     * @param float $sa
     * @return Scheda
     */
    public function setSa($sa) {
        $this->sa = $sa;

        return $this;
    }

    /**
     * Get sa
     *
     * @return float
     */
    public function getSa() {
        return $this->sa;
    }

    /**
     * Set offerta_nostra
     *
     * @param float $offertaNostra
     * @return Scheda
     */
    public function setOffertaNostra($offertaNostra) {
        $this->offerta_nostra = $offertaNostra;

        return $this;
    }

    /**
     * Get offerta_nostra
     *
     * @return float
     */
    public function getOffertaNostra() {
        return $this->offerta_nostra;
    }

    /**
     * Set offerta_loro
     *
     * @param float $offertaLoro
     * @return Scheda
     */
    public function setOffertaLoro($offertaLoro) {
        $this->offerta_loro = $offertaLoro;

        return $this;
    }

    /**
     * Get offerta_loro
     *
     * @return float
     */
    public function getOffertaLoro() {
        return $this->offerta_loro;
    }

    /**
     * Set recupero_offerta_nostra
     *
     * @param float $recuperoOffertaNostra
     * @return Scheda
     */
    public function setRecuperoOffertaNostra($recuperoOffertaNostra) {
        $this->recupero_offerta_nostra = $recuperoOffertaNostra;

        return $this;
    }

    /**
     * Get recupero_offerta_nostra
     *
     * @return float
     */
    public function getRecuperoOffertaNostra() {
        return $this->recupero_offerta_nostra;
    }

    /**
     * Set recupero_offerta_loro
     *
     * @param float $recuperoOffertaLoro
     * @return Scheda
     */
    public function setRecuperoOffertaLoro($recuperoOffertaLoro) {
        $this->recupero_offerta_loro = $recuperoOffertaLoro;

        return $this;
    }

    /**
     * Get recupero_offerta_loro
     *
     * @return float
     */
    public function getRecuperoOffertaLoro() {
        return $this->recupero_offerta_loro;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Scheda
     */
    public function setNote($note) {
        $this->note = $note;

        return $this;
    }

    /**
     * Get note
     *
     * @return string
     */
    public function getNote() {
        return $this->note;
    }

    /**
     * Set ospedale
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Ospedale $ospedale
     * @return Scheda
     */
    public function setOspedale(\Ephp\Bundle\SinistriBundle\Entity\Ospedale $ospedale = null) {
        $this->ospedale = $ospedale;

        return $this;
    }

    /**
     * Get ospedale
     *
     * @return \Ephp\Bundle\SinistriBundle\Entity\Ospedale
     */
    public function getOspedale() {
        return $this->ospedale;
    }

    /**
     * Set gestore
     *
     * @param \Ephp\Bundle\GestoriBundle\Entity\Gestore $gestore
     * @return Scheda
     */
    public function setGestore(\Ephp\Bundle\GestoriBundle\Entity\Gestore $gestore = null) {
        $this->gestore = $gestore;

        return $this;
    }

    /**
     * Get gestore
     *
     * @return \Ephp\Bundle\GestoriBundle\Entity\Gestore
     */
    public function getGestore() {
        return $this->gestore;
    }

    /**
     * Set stato
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Stato $stato
     * @return Scheda
     */
    public function setStato(\Ephp\Bundle\SinistriBundle\Entity\Stato $stato = null) {
        $this->stato = $stato;

        return $this;
    }

    /**
     * Get stato
     *
     * @return \Ephp\Bundle\SinistriBundle\Entity\Stato
     */
    public function getStato() {
        return $this->stato;
    }

    /**
     * Set priorita
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Priorita $priorita
     * @return Scheda
     */
    public function setPriorita(\Ephp\Bundle\SinistriBundle\Entity\Priorita $priorita = null) {
        $this->priorita = $priorita;

        return $this;
    }

    /**
     * Get priorita
     *
     * @return \Ephp\Bundle\SinistriBundle\Entity\Priorita
     */
    public function getPriorita() {
        return $this->priorita;
    }

    /**
     * Add eventi
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Evento $eventi
     * @return Scheda
     */
    public function addEventi(\Ephp\Bundle\SinistriBundle\Entity\Evento $eventi) {
        $this->eventi[] = $eventi;

        return $this;
    }

    /**
     * Remove eventi
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Evento $eventi
     */
    public function removeEventi(\Ephp\Bundle\SinistriBundle\Entity\Evento $eventi) {
        $this->eventi->removeElement($eventi);
    }

    /**
     * Get eventi
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventi() {
        return $this->eventi;
    }


    /**
     * Set franchigia
     *
     * @param float $franchigia
     * @return Scheda
     */
    public function setFranchigia($franchigia)
    {
        $this->franchigia = $franchigia;

        return $this;
    }

    /**
     * Get franchigia
     *
     * @return float
     */
    public function getFranchigia()
    {
        return $this->franchigia;
    }

    /**
     * Add links
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Link $links
     * @return Scheda
     */
    public function addLink(\Ephp\Bundle\SinistriBundle\Entity\Link $links)
    {
        $this->links[] = $links;
    
        return $this;
    }

    /**
     * Remove links
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Link $links
     */
    public function removeLink(\Ephp\Bundle\SinistriBundle\Entity\Link $links)
    {
        $this->links->removeElement($links);
    }

    /**
     * Get links
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLinks()
    {
        return $this->links;
    }
    /**
     * Set prima_pagina
     *
     * @param boolean $prima_pagina
     * @return Scheda
     */
    public function setPrimaPagina($prima_pagina)
    {
        $this->prima_pagina = $prima_pagina;

        return $this;
    }

    /**
     * Get prima_pagina
     *
     * @return boolean
     */
    public function getPrimaPagina()
    {
        return $this->prima_pagina;
    }

    /**
     * Set legali_avversari
     *
     * @param string $legaliAvversari
     * @return Scheda
     */
    public function setLegaliAvversari($legaliAvversari)
    {
        $this->legali_avversari = $legaliAvversari;
    
        return $this;
    }

    /**
     * Get legali_avversari
     *
     * @return string 
     */
    public function getLegaliAvversari()
    {
        return $this->legali_avversari;
    }

    /**
     * Set dati
     *
     * @param string $dati
     * @return Scheda
     */
    public function setDati($dati)
    {
        $this->dati = $dati;
    
        return $this;
    }

    /**
     * Get dati
     *
     * @return string 
     */
    public function getDati()
    {
        return $this->dati;
    }

    /**
     * Set dol
     *
     * @param \DateTime $dol
     * @return Scheda
     */
    public function setDol($dol)
    {
        $this->dol = $dol;
    
        return $this;
    }

    /**
     * Get dol
     *
     * @return \DateTime 
     */
    public function getDol()
    {
        return $this->dol;
    }

    /**
     * Set don
     *
     * @param \DateTime $don
     * @return Scheda
     */
    public function setDon($don)
    {
        $this->don = $don;
    
        return $this;
    }

    /**
     * Get don
     *
     * @return \DateTime 
     */
    public function getDon()
    {
        return $this->don;
    }

    /**
     * Set type_of_loss
     *
     * @param string $typeOfLoss
     * @return Scheda
     */
    public function setTypeOfLoss($typeOfLoss)
    {
        $this->type_of_loss = $typeOfLoss;
    
        return $this;
    }

    /**
     * Get type_of_loss
     *
     * @return string 
     */
    public function getTypeOfLoss()
    {
        return $this->type_of_loss;
    }

    /**
     * Set possible_recovery
     *
     * @param float $possibleRecovery
     * @return Scheda
     */
    public function setPossibleRecovery($possibleRecovery)
    {
        $this->possible_recovery = $possibleRecovery;
    
        return $this;
    }

    /**
     * Get possible_recovery
     *
     * @return float 
     */
    public function getPossibleRecovery()
    {
        return $this->possible_recovery;
    }

    /**
     * Set report_soi
     *
     * @param string $reportSoi
     * @return Scheda
     */
    public function setReportSoi($reportSoi)
    {
        $this->report_soi = $reportSoi;
    
        return $this;
    }

    /**
     * Get report_soi
     *
     * @return string 
     */
    public function getReportSoi()
    {
        return $this->report_soi;
    }

    /**
     * Set report_dol
     *
     * @param \DateTime $reportDol
     * @return Scheda
     */
    public function setReportDol($reportDol)
    {
        $this->report_dol = $reportDol;
    
        return $this;
    }

    /**
     * Get report_dol
     *
     * @return \DateTime 
     */
    public function getReportDol()
    {
        return $this->report_dol;
    }

    /**
     * Set report_don
     *
     * @param \DateTime $reportDon
     * @return Scheda
     */
    public function setReportDon($reportDon)
    {
        $this->report_don = $reportDon;
    
        return $this;
    }

    /**
     * Get report_don
     *
     * @return \DateTime 
     */
    public function getReportDon()
    {
        return $this->report_don;
    }

    /**
     * Set report_descrizione
     *
     * @param string $reportDescrizione
     * @return Scheda
     */
    public function setReportDescrizione($reportDescrizione)
    {
        $this->report_descrizione = $reportDescrizione;
    
        return $this;
    }

    /**
     * Get report_descrizione
     *
     * @return string 
     */
    public function getReportDescrizione()
    {
        return $this->report_descrizione;
    }

    /**
     * Set report_mpl
     *
     * @param string $reportMpl
     * @return Scheda
     */
    public function setReportMpl($reportMpl)
    {
        $this->report_mpl = $reportMpl;
    
        return $this;
    }

    /**
     * Get report_mpl
     *
     * @return string 
     */
    public function getReportMpl()
    {
        return $this->report_mpl;
    }

    /**
     * Set report_giudiziale
     *
     * @param string $reportGiudiziale
     * @return Scheda
     */
    public function setReportGiudiziale($reportGiudiziale)
    {
        $this->report_giudiziale = $reportGiudiziale;
    
        return $this;
    }

    /**
     * Get report_giudiziale
     *
     * @return string 
     */
    public function getReportGiudiziale()
    {
        return $this->report_giudiziale;
    }

    /**
     * Set report_other_policies
     *
     * @param string $reportOtherPolicies
     * @return Scheda
     */
    public function setReportOtherPolicies($reportOtherPolicies)
    {
        $this->report_other_policies = $reportOtherPolicies;
    
        return $this;
    }

    /**
     * Get report_other_policies
     *
     * @return string 
     */
    public function getReportOtherPolicies()
    {
        return $this->report_other_policies;
    }

    /**
     * Set report_type_of_loss
     *
     * @param string $reportTypeOfLoss
     * @return Scheda
     */
    public function setReportTypeOfLoss($reportTypeOfLoss)
    {
        $this->report_type_of_loss = $reportTypeOfLoss;
    
        return $this;
    }

    /**
     * Get report_type_of_loss
     *
     * @return string 
     */
    public function getReportTypeOfLoss()
    {
        return $this->report_type_of_loss;
    }

    /**
     * Set report_possible_recovery
     *
     * @param float $reportPossibleRecovery
     * @return Scheda
     */
    public function setReportPossibleRecovery($reportPossibleRecovery)
    {
        $this->report_possible_recovery = $reportPossibleRecovery;
    
        return $this;
    }

    /**
     * Get report_possible_recovery
     *
     * @return float 
     */
    public function getReportPossibleRecovery()
    {
        return $this->report_possible_recovery;
    }

    /**
     * Set report_amount_reserved
     *
     * @param float $reportAmountReserved
     * @return Scheda
     */
    public function setReportAmountReserved($reportAmountReserved)
    {
        $this->report_amount_reserved = $reportAmountReserved;
    
        return $this;
    }

    /**
     * Get report_amount_reserved
     *
     * @return float 
     */
    public function getReportAmountReserved()
    {
        return $this->report_amount_reserved;
    }

    /**
     * Set report_applicable_deductable
     *
     * @param string $reportApplicableDeductable
     * @return Scheda
     */
    public function setReportApplicableDeductable($reportApplicableDeductable)
    {
        $this->report_applicable_deductable = $reportApplicableDeductable;
    
        return $this;
    }

    /**
     * Get report_applicable_deductable
     *
     * @return string 
     */
    public function getReportApplicableDeductable()
    {
        return $this->report_applicable_deductable;
    }

    /**
     * Set report_future_conduct
     *
     * @param string $reportFutureConduct
     * @return Scheda
     */
    public function setReportFutureConduct($reportFutureConduct)
    {
        $this->report_future_conduct = $reportFutureConduct;
    
        return $this;
    }

    /**
     * Get report_future_conduct
     *
     * @return string 
     */
    public function getReportFutureConduct()
    {
        return $this->report_future_conduct;
    }

    /**
     * Set report_gestore
     *
     * @param \Ephp\Bundle\GestoriBundle\Entity\Gestore $reportGestore
     * @return Scheda
     */
    public function setReportGestore(\Ephp\Bundle\GestoriBundle\Entity\Gestore $reportGestore = null)
    {
        $this->report_gestore = $reportGestore;
    
        return $this;
    }

    /**
     * Get report_gestore
     *
     * @return \Ephp\Bundle\GestoriBundle\Entity\Gestore 
     */
    public function getReportGestore()
    {
        return $this->report_gestore;
    }

    /**
     * Set applicable_deductable
     *
     * @param float $applicableDeductable
     * @return Scheda
     */
    public function setApplicableDeductable($applicableDeductable)
    {
        $this->applicable_deductable = $applicableDeductable;
    
        return $this;
    }

    /**
     * Get applicable_deductable
     *
     * @return float 
     */
    public function getApplicableDeductable()
    {
        return $this->applicable_deductable;
    }

    /**
     * Set service_provider
     *
     * @param string $serviceProvider
     * @return Scheda
     */
    public function setServiceProvider($serviceProvider)
    {
        $this->service_provider = $serviceProvider;
    
        return $this;
    }

    /**
     * Get service_provider
     *
     * @return string 
     */
    public function getServiceProvider()
    {
        return $this->service_provider;
    }

    /**
     * Set report_service_provider
     *
     * @param string $reportServiceProvider
     * @return Scheda
     */
    public function setReportServiceProvider($reportServiceProvider)
    {
        $this->report_service_provider = $reportServiceProvider;
    
        return $this;
    }

    /**
     * Get report_service_provider
     *
     * @return string 
     */
    public function getReportServiceProvider()
    {
        return $this->report_service_provider;
    }

    /**
     * Set report_old
     *
     * @param string $reportOld
     * @return Scheda
     */
    public function setReportOld($reportOld)
    {
        $this->report_old = $reportOld;
    
        return $this;
    }

    /**
     * Get report_old
     *
     * @return string 
     */
    public function getReportOld()
    {
        return $this->report_old;
    }

    /**
     * Add reports
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Report $reports
     * @return Scheda
     */
    public function addReport(\Ephp\Bundle\SinistriBundle\Entity\Report $reports)
    {
        $this->reports[] = $reports;
    
        return $this;
    }

    /**
     * Remove reports
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Report $reports
     */
    public function removeReport(\Ephp\Bundle\SinistriBundle\Entity\Report $reports)
    {
        $this->reports->removeElement($reports);
    }

    /**
     * Get reports
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getReports()
    {
        return $this->reports;
    }
    
    public function __toString() {
        return "{$this->getOspedale()->getSigla()}/{$this->getAnno2()}/{$this->getTpa()} {$this->getClaimant()}";
    }


    /**
     * Set giudiziale
     *
     * @param string $giudiziale
     * @return Scheda
     */
    public function setGiudiziale($giudiziale)
    {
        $this->giudiziale = $giudiziale;
    
        return $this;
    }

    /**
     * Get giudiziale
     *
     * @return string 
     */
    public function getGiudiziale()
    {
        return $this->giudiziale;
    }
}