<?php

namespace B2S\Bundle\PortletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B2S\Bundle\PortletBundle\Entity\PortletPagina
 *
 * @ORM\Table(name="portlet_composizione_pagine")
 * @ORM\Entity(repositoryClass="B2S\Bundle\PortletBundle\Entity\PortletPaginaRepository")
 */
class PortletPagina
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
     * @var integer $pagina
     *
     * @ORM\ManyToOne(targetEntity="Pagina", inversedBy="portlets")
     * @ORM\JoinColumn(name="pagina_id", referencedColumnName="id")
     */
    private $pagina;

    /**
     * @var integer $portlet
     *
     * @ORM\ManyToOne(targetEntity="Portlet")
     * @ORM\JoinColumn(name="portlet_id", referencedColumnName="id")
     */
    private $portlet;

    /**
     * @var string $area_layout
     *
     * @ORM\Column(name="area_layout", type="string", length=16)
     */
    private $area_layout;

    /**
     * @var integer $ordine
     *
     * @ORM\Column(name="ordine", type="integer")
     */
    private $ordine;

    /**
     * @var boolean $ordine_edit
     *
     * @ORM\Column(name="ordine_edit", type="boolean")
     */
    private $ordine_edit;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Configurazione", mappedBy="portlet", cascade={"persist", "detach", "remove", "merge", "refresh"}, orphanRemoval=true)
     */
    private $proprieta;
    
    
    function __construct() {
        $this->proprieta = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set pagina
     *
     * @param integer $pagina
     */
    public function setPagina($pagina)
    {
        $this->pagina = $pagina;
    }

    /**
     * Get pagina
     *
     * @return integer 
     */
    public function getPagina()
    {
        return $this->pagina;
    }

    /**
     * Set portlet
     *
     * @param integer $portlet
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
     * Set area_layout
     *
     * @param string $areaLayout
     */
    public function setAreaLayout($areaLayout)
    {
        $this->area_layout = $areaLayout;
    }

    /**
     * Get area_layout
     *
     * @return string 
     */
    public function getAreaLayout()
    {
        return $this->area_layout;
    }

    /**
     * Set ordine
     *
     * @param integer $ordine
     */
    public function setOrdine($ordine)
    {
        $this->ordine = $ordine;
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
     * Set ordine_edit
     *
     * @param boolean $ordineEdit
     */
    public function setOrdineEdit($ordineEdit)
    {
        $this->ordine_edit = $ordineEdit;
    }

    /**
     * Get ordine_edit
     *
     * @return boolean 
     */
    public function getOrdineEdit()
    {
        return $this->ordine_edit;
    }

    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getProprieta() {
        return $this->proprieta;
    }
    
    /**
     * 
     * @return array
     */
    public function getParams() {
        $out = array();
        foreach ($this->proprieta as $proprieta) {
//            $proprieta = new Configurazione();
            $out[$proprieta->getProprieta()] = $proprieta->getValore();
        }
        return $out;
    }

    /**
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $proprieta
     */
    public function setProprieta($proprieta) {
        $this->proprieta = $proprieta;
    }

    public function addProprieta($proprieta) {
        $this->proprieta->add($proprieta);
    }

}