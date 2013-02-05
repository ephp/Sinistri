<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Config;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\Bundle\WsInvokerBundle\Entity\Config\Host
 *
 * @ORM\Table(name="ws_hosts", uniqueConstraints={@ORM\UniqueConstraint(name="name_idx", columns={"name"})})
 * @ORM\Entity(repositoryClass="Ephp\Bundle\WsInvokerBundle\Entity\Config\HostRepository")
 */
class Host
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
     * @var integer $group_id
     */
    private $group_id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=32)
     */
    private $name;
    
    /**
     * @var string $host
     *
     * @ORM\Column(name="host", type="string", length=255)
     */
    private $host;

    /**
     * @var string $host
     *
     * @ORM\Column(name="host_dev", type="string", length=255)
     */
    private $host_dev;

    /**
     * @var string $note
     *
     * @ORM\Column(name="note", type="string", length=32)
     */
    private $note;

    /**
     * @var datetime $last_call_at
     *
     * @ORM\Column(name="last_call_at", type="datetime", nullable=true)
     */
    private $last_call_at;

    /**
     * @var string $last_status_code
     *
     * @ORM\Column(name="last_status_code", type="string", length=3, nullable=true)
     */
    private $last_status_code;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="hosts")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=true)
     */
    private $group;

    /**
     * @ORM\OneToMany(targetEntity="Service", mappedBy="host")
     */
    private $services;

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
     * Set group_id
     *
     * @param integer $groupId
     */
    public function setGroupId($groupId)
    {
        $this->group_id = $groupId;
    }

    /**
     * Get group_id
     *
     * @return integer 
     */
    public function getGroupId()
    {
        return $this->group_id;
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
     * Set host
     *
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * Get host
     *
     * @return string 
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set host_dev
     *
     * @param string $host_dev
     */
    public function setHostDev($host_dev)
    {
        $this->host_dev = $host_dev;
    }

    /**
     * Get host_dev
     *
     * @return string 
     */
    public function getHostDev()
    {
        return $this->host_dev;
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
     * Set last_call_at
     *
     * @param datetime $lastCallAt
     */
    public function setLastCallAt($lastCallAt)
    {
        $this->last_call_at = $lastCallAt;
    }

    /**
     * Get last_call_at
     *
     * @return datetime 
     */
    public function getLastCallAt()
    {
        return $this->last_call_at;
    }

    /**
     * Set last_status_code
     *
     * @param string $lastStatusCode
     */
    public function setLastStatusCode($lastStatusCode)
    {
        $this->last_status_code = $lastStatusCode;
    }

    /**
     * Get last_status_code
     *
     * @return string 
     */
    public function getLastStatusCode()
    {
        return $this->last_status_code;
    }

    /**
     * Set group
     *
     * @param Group $group
     */
    public function setGroup($group) {
        $this->group = $group;
    }

    /**
     * Get group
     *
     * @return Group 
     */
    public function getGroup() {
        return $this->group;
    }

    /**
     * Set services
     *
     * @param array $services
     */
    public function setServices($services) {
        $this->services = $services;
    }

    /**
     * Get services
     *
     * @return array 
     */
    public function getServices() {
        return $this->services;
    }

    public function __toString() {
        return $this->getName();
    }
    
}