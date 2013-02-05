<?php

namespace B2S\Bundle\PortletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B2S\Bundle\PortletBundle\Entity\Configurazione
 *
 * @ORM\Table(name="portlet_configurazione")
 * @ORM\Entity(repositoryClass="B2S\Bundle\PortletBundle\Entity\ConfigurazioneRepository")
 */
class Configurazione
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
     * @var integer $portlet
     *
     * @ORM\ManyToOne(targetEntity="PortletPagina", inversedBy="proprieta")
     * @ORM\JoinColumn(name="portlet_id", referencedColumnName="id")
     */
    private $portlet;

    /**
     * @var string $proprieta
     *
     * @ORM\Column(name="proprieta", type="string", length=32)
     */
    private $proprieta;

    /**
     * @var integer $progressivo
     *
     * @ORM\Column(name="progressivo", type="integer")
     */
    private $progressivo;

    /**
     * @var text $valore
     *
     * @ORM\Column(name="valore", type="text")
     */
    private $valore;


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
     * @param integer $portlet
     */
    public function setPortlet($portlet)
    {
        $this->portlet = $portlet;
    }

    /**
     * Get portlet
     *
     * @return integer 
     */
    public function getPortlet()
    {
        return $this->portlet;
    }

    /**
     * Set proprieta
     *
     * @param integer $proprieta
     */
    public function setProprieta($proprieta)
    {
        $this->proprieta = $proprieta;
    }

    /**
     * Get proprieta
     *
     * @return integer 
     */
    public function getProprieta()
    {
        return $this->proprieta;
    }

    /**
     * Set progressivo
     *
     * @param integer $progressivo
     */
    public function setProgressivo($progressivo)
    {
        $this->progressivo = $progressivo;
    }

    /**
     * Get progressivo
     *
     * @return integer 
     */
    public function getProgressivo()
    {
        return $this->progressivo;
    }

    /**
     * Set valore
     *
     * @param text $valore
     */
    public function setValore($valore)
    {
        $this->valore = $valore;
    }

    /**
     * Get valore
     *
     * @return text 
     */
    public function getValore()
    {
        return $this->valore;
    }
}