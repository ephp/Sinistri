<?php

namespace Ephp\Bundle\SinistriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Priorita
 *
 * @ORM\Table(name="sx_priorita")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\SinistriBundle\Entity\PrioritaRepository")
 */
class Priorita
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
     * @var string
     *
     * @ORM\Column(name="priorita", type="string", length=255)
     */
    private $priorita;

    /**
     * @var string
     *
     * @ORM\Column(name="on_change", type="string", length=255)
     */
    private $on_change;

    /**
     * @var string
     *
     * @ORM\Column(name="css", type="string", length=255)
     */
    private $css;

    /**
     * @var string
     *
     * @ORM\Column(name="show", type="boolean")
     */
    private $show;


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
     * Set priorita
     *
     * @param string $priorita
     * @return Priorita
     */
    public function setPriorita($priorita)
    {
        $this->priorita = $priorita;
    
        return $this;
    }

    /**
     * Get priorita
     *
     * @return string 
     */
    public function getPriorita()
    {
        return $this->priorita;
    }
    
    /**
     * Get priorita
     *
     * @return string 
     */
    public function __toString()
    {
        return $this->priorita;
    }

    /**
     * Set on_change
     *
     * @param string $onChange
     * @return Priorita
     */
    public function setOnChange($onChange)
    {
        $this->on_change = $onChange;
    
        return $this;
    }

    /**
     * Get on_change
     *
     * @return string 
     */
    public function getOnChange()
    {
        return $this->on_change;
    }

    /**
     * Set css
     *
     * @param string $css
     * @return Priorita
     */
    public function setCss($css)
    {
        $this->css = $css;
    
        return $this;
    }

    /**
     * Get css
     *
     * @return string 
     */
    public function getCss()
    {
        return $this->css;
    }

    /**
     * Set show
     *
     * @param boolean $show
     * @return Priorita
     */
    public function setShow($show)
    {
        $this->show = $show;
    
        return $this;
    }

    /**
     * Get show
     *
     * @return boolean 
     */
    public function getShow()
    {
        return $this->show;
    }
    
    
}