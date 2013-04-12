<?php

namespace Ephp\Bundle\SinistriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stato
 *
 * @ORM\Table(name="sx_stati")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\SinistriBundle\Entity\StatoRepository")
 */
class Stato
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
     * @ORM\Column(name="stato", type="string", length=255)
     */
    private $stato;

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
     * @return Stato
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
    
    public function __toString()
    {
        return $this->stato;
    }

}