<?php

namespace Ephp\Bundle\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tipo
 *
 * @ORM\Table(name="cal_tipi")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\CalendarBundle\Entity\TipoRepository")
 */
class Tipo
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
     * @var string
     *
     * @ORM\Column(name="sigla", type="string", length=3)
     */
    private $sigla;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="colore", type="string", length=6)
     */
    private $colore;


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
     * @return Tipo
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
     * Set sigla
     *
     * @param string $sigla
     * @return Tipo
     */
    public function setSigla($sigla)
    {
        $this->sigla = $sigla;
    
        return $this;
    }

    /**
     * Get sigla
     *
     * @return string 
     */
    public function getSigla()
    {
        return $this->sigla;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return Tipo
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    
        return $this;
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
     * Set colore
     *
     * @param string $colore
     * @return Tipo
     */
    public function setColore($colore)
    {
        $this->colore = $colore;
    
        return $this;
    }

    /**
     * Get colore
     *
     * @return string 
     */
    public function getColore()
    {
        return $this->colore;
    }
}