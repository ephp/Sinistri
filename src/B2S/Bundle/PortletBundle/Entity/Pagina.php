<?php

namespace B2S\Bundle\PortletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * B2S\Bundle\PortletBundle\Entity\Pagina
 *
 * @ORM\Table(name="portlet_pagine")
 * @ORM\Entity(repositoryClass="B2S\Bundle\PortletBundle\Entity\PaginaRepository")
 */
class Pagina
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
     * @var Area $area
     *
     * @ORM\ManyToOne(targetEntity="Area")
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id")
     */
    private $area;

    /**
     * @var Layout $layout
     *
     * @ORM\ManyToOne(targetEntity="Layout")
     * @ORM\JoinColumn(name="layout_id", referencedColumnName="id")
     */
    private $layout;

    /**
     * @var string $titolo
     *
     * @ORM\Column(name="titolo", type="string", length=32)
     */
    private $titolo;

    /**
     * @var boolean $titolo_edit
     *
     * @ORM\Column(name="titolo_edit", type="boolean")
     */
    private $titolo_edit;

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
     * @var boolean $cancellabile
     *
     * @ORM\Column(name="cancellabile", type="boolean")
     */
    private $cancellabile;

    /**
     * @Gedmo\Slug(fields={"titolo"}, updatable=true, unique=false)
     * @ORM\Column(name="slug", type="string", length=255, unique=false)
     */
    private $slug;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="PortletPagina", mappedBy="pagina", cascade={"persist", "remove", "merge", "refresh"}, orphanRemoval=true)
     */
    private $portlets;

    /**
     * @var string $visibilita
     *
     * @ORM\Column(name="visibilita", type="text", nullable=true)
     */
    private $visibilita;

    function __construct() {
        $this->portlets = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set area
     *
     * @param Area $area
     */
    public function setArea($area)
    {
        $this->area = $area;
    }

    /**
     * Get area
     *
     * @return Area 
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set layout
     *
     * @param Layout $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Get layout
     *
     * @return Layout 
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set titolo
     *
     * @param string $titolo
     */
    public function setTitolo($titolo)
    {
        $this->titolo = $titolo;
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
     * Set titolo_edit
     *
     * @param boolean $titoloEdit
     */
    public function setTitoloEdit($titoloEdit)
    {
        $this->titolo_edit = $titoloEdit;
    }

    /**
     * Get titolo_edit
     *
     * @return boolean 
     */
    public function getTitoloEdit()
    {
        return $this->titolo_edit;
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
     * Set cancellabile
     *
     * @param boolean $cancellabile
     */
    public function setCancellabile($cancellabile)
    {
        $this->cancellabile = $cancellabile;
    }

    /**
     * Get cancellabile
     *
     * @return boolean 
     */
    public function getCancellabile()
    {
        return $this->cancellabile;
    }
    
    public function getPortlets() {
        return $this->portlets;
    }

    public function setPortlets($portlets) {
        $this->portlets = $portlets;
    }
    
    public function addPortlets($portlet) {
        $this->portlets->add($portlet);
    }
    
    public function getSlug() {
        return $this->slug;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
    }
    
    public function getVisibilita() {
        return $this->visibilita;
    }

    public function setVisibilita($visibilita) {
        $this->visibilita = $visibilita;
    }

}