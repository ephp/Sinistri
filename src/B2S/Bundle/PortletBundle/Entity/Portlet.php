<?php

namespace B2S\Bundle\PortletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B2S\Bundle\PortletBundle\Entity\Portlet
 *
 * @ORM\Table(name="portlet_catalogo")
 * @ORM\Entity(repositoryClass="B2S\Bundle\PortletBundle\Entity\PortletRepository")
 */
class Portlet
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
     * @var string $nome
     *
     * @ORM\Column(name="nome", type="string", length=64)
     */
    private $nome;

    /**
     * @var string $bundle
     *
     * @ORM\Column(name="bundle", type="string", length=64)
     */
    private $bundle;

    /**
     * @var string $action
     *
     * @ORM\Column(name="action", type="string", length=64)
     */
    private $action;

    /**
     * @var string $layout
     *
     * @ORM\Column(name="layout", type="string", length=64)
     */
    private $layout;

    /**
     * @var text $descrizione
     *
     * @ORM\Column(name="descrizione", type="text")
     */
    private $descrizione;

    /**
     * @var boolean $pubblico
     *
     * @ORM\Column(name="pubblico", type="boolean")
     */
    private $pubblico;

    /**
     * @var boolean $privato
     *
     * @ORM\Column(name="privato", type="boolean")
     */
    private $privato;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Proprieta", mappedBy="portlet", cascade={"persist", "remove", "merge", "refresh"}, orphanRemoval=true)
     */
    private $proprieta;
    
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection $aree
     *
     * @ORM\ManyToMany(targetEntity="Portlet", inversedBy="catalogo", cascade={"persist", "merge", "refresh"})
     * @ORM\JoinTable(name="portlet_catalogo_aree",
     *      joinColumns={@ORM\JoinColumn(name="portlet_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="area_id", referencedColumnName="id")}
     *      )
     */
    private $aree;
    
    function __construct() {
        $this->proprieta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->aree = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nome
     *
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    /**
     * Get nome
     *
     * @return string 
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set bundle
     *
     * @param string $bundle
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;
    }

    /**
     * Get bundle
     *
     * @return string 
     */
    public function getBundle()
    {
        return str_replace('EcoSeekr', 'B2S', $this->bundle);
    }

    /**
     * Set action
     *
     * @param string $action
     */
    public function setAction($action)
    {
        $this->action = $action;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set layout
     *
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Get layout
     *
     * @return string 
     */
    public function getLayout()
    {
        return $this->layout;
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
     * Set pubblico
     *
     * @param boolean $pubblico
     */
    public function setPubblico($pubblico)
    {
        $this->pubblico = $pubblico;
    }

    /**
     * Get pubblico
     *
     * @return boolean 
     */
    public function getPubblico()
    {
        return $this->pubblico;
    }

    /**
     * Set privato
     *
     * @param boolean $privato
     */
    public function setPrivato($privato)
    {
        $this->privato = $privato;
    }

    /**
     * Get privato
     *
     * @return boolean 
     */
    public function getPrivato()
    {
        return $this->privato;
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
     * @param \Doctrine\Common\Collections\ArrayCollection $proprieta
     */
    public function setProprieta($proprieta) {
        $this->proprieta = $proprieta;
    }
    
    /**
     * 
     * @param Proprieta $proprieta
     */
    public function addProprieta($proprieta) {
        $this->proprieta->add($proprieta);
    }
    
    /**
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getAree() {
        return $this->aree;
    }

    /**
     * 
     * @param \Doctrine\Common\Collections\ArrayCollection $area
     */
    public function setAree($area) {
        $this->aree = $area;
    }
    
    /**
     * 
     * @param Area $area
     */
    public function addAree($area) {
        $this->aree->add($area);
    }


}