<?php

namespace B2S\Bundle\PortletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B2S\Bundle\PortletBundle\Entity\Area
 *
 * @ORM\Table(name="portlet_aree")
 * @ORM\Entity(repositoryClass="B2S\Bundle\PortletBundle\Entity\AreaRepository")
 */
class Area
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
     * @var \Doctrine\Common\Collections\ArrayCollection $catalogo
     *
     * @ORM\ManyToMany(targetEntity="Portlet", mappedBy="aree", cascade={"persist", "merge", "refresh"})
     */
    private $catalogo;

    function __construct() {
        $this->catalogo = new \Doctrine\Common\Collections\ArrayCollection();
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
        return $this->bundle;
    }

    /**
     * Set catalogo
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $catalogo
     */
    public function setCatalogo($catalogo)
    {
        $this->catalogo = $catalogo;
    }

    /**
     * Get catalogo
     *
     * @return \Doctrine\Common\Collections\ArrayCollection 
     */
    public function getCatalogo()
    {
        return $this->catalogo;
    }
    
    /**
     * Set catalogo
     *
     * @param Portlet $catalogo
     */
    public function addCatalogo($catalogo)
    {
        $this->catalogo->add($catalogo);
    }
}