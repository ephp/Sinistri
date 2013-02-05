<?php

namespace B2S\Bundle\PortletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B2S\Bundle\PortletBundle\Entity\Proprieta
 *
 * @ORM\Table(name="portlet_proprieta")
 * @ORM\Entity(repositoryClass="B2S\Bundle\PortletBundle\Entity\ProprietaRepository")
 */
class Proprieta
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Portlet $portlet
     *
     * @ORM\ManyToOne(targetEntity="Portlet", inversedBy="proprieta")
     * @ORM\JoinColumn(name="portlet_id", referencedColumnName="id")
     */
    private $portlet;

    /**
     * @var string $proprieta
     *
     * @ORM\Column(name="proprieta", type="string", length=64)
     */
    private $proprieta;

    /**
     * @var text $descrizione
     *
     * @ORM\Column(name="descrizione", type="text")
     */
    private $descrizione;

    /**
     * @var integer $tipo
     *
     * @ORM\Column(name="tipo", type="integer")
     */
    private $tipo;

    /**
     * @var boolean $multipla
     *
     * @ORM\Column(name="multipla", type="boolean")
     */
    private $multipla;


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
     * Set portlet
     *
     * @param Portlet $portlet
     */
    public function setPortlet($portlet)
    {
        $this->portlet = $portlet;
    }

    /**
     * Get portlet
     *
     * @return Portlet 
     */
    public function getPortlet()
    {
        return $this->portlet;
    }

    /**
     * Set proprieta
     *
     * @param string $proprieta
     */
    public function setProprieta($proprieta)
    {
        $this->proprieta = $proprieta;
    }

    /**
     * Get proprieta
     *
     * @return string 
     */
    public function getProprieta()
    {
        return $this->proprieta;
    }

    /**
     * Set descrizione
     *
     * @param text $descrizione
     */
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;
    }

    /**
     * Get descrizione
     *
     * @return text 
     */
    public function getDescrizione()
    {
        return $this->descrizione;
    }

    /**
     * Set tipo
     *
     * @param integer $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Get tipo
     *
     * @return integer 
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set multipla
     *
     * @param boolean $multipla
     */
    public function setMultipla($multipla)
    {
        $this->multipla = $multipla;
    }

    /**
     * Get multipla
     *
     * @return boolean 
     */
    public function getMultipla()
    {
        return $this->multipla;
    }
}