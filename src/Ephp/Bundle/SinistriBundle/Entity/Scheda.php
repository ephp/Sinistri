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
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Evento", mappedBy="scheda", cascade={"persist", "remove", "merge", "refresh"}, orphanRemoval=true)
     * @ORM\OrderBy({"data_ora" = "ASC"})
     */
    private $eventi;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Link", mappedBy="scheda", cascade={"persist", "remove", "merge", "refresh"}, orphanRemoval=true)
     * @ORM\OrderBy({"sito" = "ASC"})
     */
    private $links;

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
}