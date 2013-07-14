<?php

namespace Ephp\Bundle\SinistriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Priorita
 *
 * @ORM\Table(name="sx_sistema")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\SinistriBundle\Entity\SistemaRepository")
 */
class Sistema
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
     * @ORM\Column(name="sigla", type="string", length=4)
     */
    private $sigla;

    /**
     * @var string
     *
     * @ORM\Column(name="sistema", type="string", length=255)
     */
    private $sistema;

    
    

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
     * Set sigla
     *
     * @param string $sigla
     * @return Sistema
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
     * Set sistema
     *
     * @param string $sistema
     * @return Sistema
     */
    public function setSistema($sistema)
    {
        $this->sistema = $sistema;
    
        return $this;
    }

    /**
     * Get sistema
     *
     * @return string 
     */
    public function getSistema()
    {
        return $this->sistema;
    }
}
