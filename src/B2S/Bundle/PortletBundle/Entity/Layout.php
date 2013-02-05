<?php

namespace B2S\Bundle\PortletBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B2S\Bundle\PortletBundle\Entity\Layout
 *
 * @ORM\Table(name="portlet_layout")
 * @ORM\Entity(repositoryClass="B2S\Bundle\PortletBundle\Entity\LayoutRepository")
 */
class Layout
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
     * @var string $twig
     *
     * @ORM\Column(name="twig", type="string", length=255)
     */
    private $twig;

    /**
     * @var text $descrizione
     *
     * @ORM\Column(name="descrizione", type="text")
     */
    private $descrizione;


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
     * Set twig
     *
     * @param string $twig
     */
    public function setTwig($twig)
    {
        $this->twig = $twig;
    }

    /**
     * Get twig
     *
     * @return string 
     */
    public function getTwig()
    {
        return str_replace('EcoSeekr', 'B2S', $this->twig);
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
}