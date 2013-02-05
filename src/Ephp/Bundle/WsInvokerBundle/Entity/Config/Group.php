<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Config;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\Bundle\WsInvokerBundle\Entity\Group
 *
 * @ORM\Table(name="ws_groups", uniqueConstraints={@ORM\UniqueConstraint(name="name_idx", columns={"name"})})
 * @ORM\Entity(repositoryClass="Ephp\Bundle\WsInvokerBundle\Entity\Config\GroupRepository")
 */
class Group
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=32)
     */
    private $name;

    /**
     * @var text $note
     *
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\OneToMany(targetEntity="Host", mappedBy="group")
     */
    private $hosts;

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set note
     *
     * @param text $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * Get note
     *
     * @return text 
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set hosts
     *
     * @param array $hosts
     */
    public function setHosts($hosts) {
        $this->hosts = $hosts;
    }

    /**
     * Get hosts
     *
     * @return array of Host 
     */
    public function getHosts() {
        return $this->hosts;
    }

    public function __toString() {
        return $this->getName();
    }
    
}