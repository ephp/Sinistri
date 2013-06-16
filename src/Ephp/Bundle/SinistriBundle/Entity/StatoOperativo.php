<?php

namespace Ephp\Bundle\SinistriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StatoOperativo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Ephp\Bundle\SinistriBundle\Entity\StatoOperativoRepository")
 */
class StatoOperativo
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
     * @ORM\Column(name="stato", type="string", length=32)
     */
    private $stato;

    /**
     * @var boolean
     *
     * @ORM\Column(name="tab", type="boolean", nullable=true)
     */
    private $tab;

    /**
     * @var boolean
     *
     * @ORM\Column(name="stats", type="boolean", nullable=true)
     */
    private $stats;

    /**
     * @var boolean
     *
     * @ORM\Column(name="primo", type="boolean", nullable=true)
     */
    private $primo;


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
     * Set stato
     *
     * @param string $stato
     * @return StatoOperativo
     */
    public function setStato($stato)
    {
        $this->stato = $stato;
    
        return $this;
    }

    /**
     * Get stato
     *
     * @return string 
     */
    public function getStato()
    {
        return $this->stato;
    }

    /**
     * Set tab
     *
     * @param boolean $tab
     * @return StatoOperativo
     */
    public function setTab($tab)
    {
        $this->tab = $tab;
    
        return $this;
    }

    /**
     * Get tab
     *
     * @return boolean 
     */
    public function getTab()
    {
        return $this->tab;
    }

    /**
     * Set stats
     *
     * @param boolean $stats
     * @return StatoOperativo
     */
    public function setStats($stats)
    {
        $this->stats = $stats;
    
        return $this;
    }

    /**
     * Get stats
     *
     * @return boolean 
     */
    public function getStats()
    {
        return $this->stats;
    }

    /**
     * Set primo
     *
     * @param boolean $primo
     * @return StatoOperativo
     */
    public function setPrimo($primo)
    {
        $this->primo = $primo;
    
        return $this;
    }

    /**
     * Get primo
     *
     * @return boolean 
     */
    public function getPrimo()
    {
        return $this->primo;
    }
}
