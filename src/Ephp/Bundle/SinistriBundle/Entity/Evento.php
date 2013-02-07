<?php

namespace Ephp\Bundle\SinistriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evento
 *
 * @ORM\Table(name="cal_eventi_sx")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\SinistriBundle\Entity\EventoRepository")
 */
class Evento extends \Ephp\Bundle\CalendarBundle\Entity\Evento
{
    /**
     * @ORM\ManyToOne(targetEntity="Scheda")
     * @ORM\JoinColumn(name="scheda_id", referencedColumnName="id")
     */
    private $scheda;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordine", type="integer")
     */
    private $ordine;

    /**
     * @var integer
     *
     * @ORM\Column(name="delta_g", type="integer", nullable=true)
     */
    private $delta_g;

    /**
     * Set ordine
     *
     * @param integer $ordine
     * @return Evento
     */
    public function setOrdine($ordine)
    {
        $this->ordine = $ordine;
    
        return $this;
    }

    /**
     * Get ordine
     *
     * @return integer 
     */
    public function getOrdine()
    {
        return $this->ordine;
    }

    /**
     * Set delta_g
     *
     * @param integer $deltaG
     * @return Evento
     */
    public function setDeltaG($deltaG)
    {
        $this->delta_g = $deltaG;
    
        return $this;
    }

    /**
     * Get delta_g
     *
     * @return integer 
     */
    public function getDeltaG()
    {
        return $this->delta_g;
    }

    /**
     * Set scheda
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Scheda $scheda
     * @return Evento
     */
    public function setScheda(\Ephp\Bundle\SinistriBundle\Entity\Scheda $scheda = null)
    {
        $this->scheda = $scheda;
    
        return $this;
    }

    /**
     * Get scheda
     *
     * @return \Ephp\Bundle\SinistriBundle\Entity\Scheda 
     */
    public function getScheda()
    {
        return $this->scheda;
    }
}