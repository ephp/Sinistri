<?php

namespace Ephp\Bundle\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table(name="cal_eventi")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\CalendarBundle\Entity\EventoRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *          "Sinistri"                     = "Ephp\Bundle\SinistriBundle\Entity\Evento",
 *          "null"                         = "Evento" 
 * })
 * @ORM\HasLifecycleCallbacks
 */
abstract class Evento
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Calendario")
     * @ORM\JoinColumn(name="calendario_id", referencedColumnName="id")
     */
    private $calendario;

    /**
     * @ORM\ManyToOne(targetEntity="Tipo")
     * @ORM\JoinColumn(name="tipo_id", referencedColumnName="id", nullable=true)
     */
    private $tipo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_ora", type="datetime")
     */
    private $data_ora;
   
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="giorno_intero", type="boolean", nullable=true)
     */
    private $giorno_intero;

    /**
     * @var string
     *
     * @ORM\Column(name="titolo", type="string", length=255)
     */
    private $titolo;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="text")
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="importante", type="boolean", nullable=true)
     */
    private $importante;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rischedulazione", type="boolean", nullable=true)
     */
    private $rischedulazione;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set calendario
     *
     * @param integer $calendario
     * @return Evento
     */
    public function setCalendario($calendario)
    {
        $this->calendario = $calendario;
    
        return $this;
    }

    /**
     * Get calendario
     *
     * @return integer 
     */
    public function getCalendario()
    {
        return $this->calendario;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     * @return Evento
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    
        return $this;
    }

    /**
     * Get tipo
     *
     * @return Tipo 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set data
     *
     * @param \DateTime $data
     * @return Evento
     */
    public function setData($data)
    {
        $this->data = $data;
    
        return $this;
    }

    /**
     * Get data
     *
     * @return \DateTime 
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set ora
     *
     * @param \DateTime $ora
     * @return Evento
     */
    public function setOra($ora)
    {
        $this->ora = $ora;
    
        return $this;
    }

    /**
     * Get ora
     *
     * @return \DateTime 
     */
    public function getOra()
    {
        return $this->ora;
    }

    /**
     * Set data_ora
     *
     * @param \DateTime $dataOra
     * @return Evento
     */
    public function setDataOra($dataOra)
    {
        $this->data_ora = $dataOra;
    
        return $this;
    }

    /**
     * Get data_ora
     *
     * @return \DateTime 
     */
    public function getDataOra()
    {
        return $this->data_ora;
    }

    /**
     * Set titolo
     *
     * @param string $titolo
     * @return Evento
     */
    public function setTitolo($titolo)
    {
        $this->titolo = $titolo;
    
        return $this;
    }

    /**
     * Get titolo
     *
     * @return string 
     */
    public function getTitolo()
    {
        return $this->titolo;
    }

    /**
     * Set note
     *
     * @param string $note
     * @return Evento
     */
    public function setNote($note)
    {
        $this->note = $note;
    
        return $this;
    }

    /**
     * Get note
     *
     * @return string 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set giorno_intero
     *
     * @param boolean $giornoIntero
     * @return Evento
     */
    public function setGiornoIntero($giornoIntero)
    {
        $this->giorno_intero = $giornoIntero;
    
        return $this;
    }

    /**
     * Get giorno_intero
     *
     * @return boolean 
     */
    public function getGiornoIntero()
    {
        return $this->giorno_intero;
    }

    /**
     * Set importante
     *
     * @param boolean $importante
     * @return Evento
     */
    public function setImportante($importante)
    {
        $this->importante = $importante;
    
        return $this;
    }

    /**
     * Get importante
     *
     * @return boolean 
     */
    public function getImportante()
    {
        return $this->importante;
    }

    /**
     * Set rischedulazione
     *
     * @param boolean $rischedulazione
     * @return Evento
     */
    public function setRischedulazione($rischedulazione)
    {
        $this->rischedulazione = $rischedulazione;
    
        return $this;
    }

    /**
     * Get rischedulazione
     *
     * @return boolean 
     */
    public function getRischedulazione()
    {
        return $this->rischedulazione;
    }
    
    abstract public function getTwig();
}