<?php

namespace Ephp\Bundle\GestoriBundle\Entity;

use Ephp\ACLBundle\Model\BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Gestore
 *
 * @ORM\Table(name="acl_gestori")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\GestoriBundle\Entity\GestoreRepository")
 */
class Gestore extends BaseUser {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sigla", type="string", length=3)
     */
    protected $sigla;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255)
     */
    protected $nome;

    /**
     * @var boolean
     *
     * @ORM\Column(name="superadmin", type="boolean", nullable=true)
     */
    protected $superadmin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="admin", type="boolean", nullable=true)
     */
    protected $admin;

    /**
     * @var boolean
     *
     * @ORM\Column(name="coordinatore", type="boolean", nullable=true)
     */
    protected $coordinatore;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gestore", type="boolean", nullable=true)
     */
    protected $gestore;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set sigla
     *
     * @param string $sigla
     * @return Gestore
     */
    public function setSigla($sigla) {
        $this->sigla = $sigla;

        return $this;
    }

    /**
     * Get sigla
     *
     * @return string
     */
    public function getSigla() {
        return $this->sigla;
    }

    /**
     * Set nome
     *
     * @param string $nome
     * @return Gestore
     */
    public function setNome($nome) {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /**
     * Set superadmin
     *
     * @param boolean $superadmin
     * @return Gestore
     */
    public function setSuperadmin($superadmin)
    {
        $this->superadmin = $superadmin;
    
        return $this;
    }

    /**
     * Get superadmin
     *
     * @return boolean 
     */
    public function getSuperadmin()
    {
        return $this->superadmin;
    }

    /**
     * Set admin
     *
     * @param boolean $admin
     * @return Gestore
     */
    public function setAdmin($admin)
    {
        $this->admin = $admin;
    
        return $this;
    }

    /**
     * Get admin
     *
     * @return boolean 
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set coordinatore
     *
     * @param boolean $coordinatore
     * @return Gestore
     */
    public function setCoordinatore($coordinatore)
    {
        $this->coordinatore = $coordinatore;
    
        return $this;
    }

    /**
     * Get coordinatore
     *
     * @return boolean 
     */
    public function getCoordinatore()
    {
        return $this->coordinatore;
    }

    /**
     * Set gestore
     *
     * @param boolean $gestore
     * @return Gestore
     */
    public function setGestore($gestore)
    {
        $this->gestore = $gestore;
    
        return $this;
    }

    /**
     * Get gestore
     *
     * @return boolean 
     */
    public function getGestore()
    {
        return $this->gestore;
    }
}