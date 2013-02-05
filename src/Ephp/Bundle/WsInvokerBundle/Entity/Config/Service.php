<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Config;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\Bundle\WsInvokerBundle\Entity\Config\Service
 *
 * @ORM\Table(name="ws_services", uniqueConstraints={@ORM\UniqueConstraint(name="name_idx", columns={"host_id", "name"})})
 * @ORM\Entity(repositoryClass="Ephp\Bundle\WsInvokerBundle\Entity\Config\ServiceRepository")
 */
class Service
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
     * @var integer $host_id
     */
    private $host_id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=32)
     */
    private $name;

    /**
     * @var string $method
     *
     * @ORM\Column(name="method", type="string", length=7)
     */
    private $method;

    /**
     * @var string $uri
     *
     * @ORM\Column(name="uri", type="string", length=255)
     */
    private $uri;

    /**
     * @var text $query_string
     *
     * @ORM\Column(name="query_string", type="string", length=255, nullable=true)
     */
    private $query_string;

    /**
     * @var text $data_type
     *
     * @ORM\Column(name="data_type", type="string", length=32, nullable=true)
     */
    private $data_type;
    
    /**
     * @var text $data_body
     *
     * @ORM\Column(name="data_body", type="text", nullable=true)
     */
    private $data_body;

    /**
     * @var text $header
     *
     * @ORM\Column(name="header", type="text", nullable=true)
     */
    private $header;
    
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
     * @ORM\ManyToOne(targetEntity="Host", inversedBy="services")
     * @ORM\JoinColumn(name="host_id", referencedColumnName="id")
     */
    private $host;

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
     * Set host_id
     *
     * @param integer $host_id
     */
    public function setHostId($host_id) {
        $this->host_id = $host_id;
    }

    /**
     * Get host_id
     *
     * @return integer 
     */
    public function getHostId() {
        return $this->host_id;
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
     * Set method
     *
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * Get method
     *
     * @return string 
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set uri
     *
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Get uri
     *
     * @return string 
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Set query_string
     *
     * @param text $query_string
     */
    public function setQueryString($query_string)
    {
        $this->query_string = $query_string;
    }

    /**
     * Get query_string
     *
     * @return text 
     */
    public function getQueryString()
    {
        return $this->query_string;
    }

    /**
     * Set data_type
     *
     * @param text $data_type
     */
    public function setDataType($data_type)
    {
        $this->data_type = $data_type;
    }

    /**
     * Get data_type
     *
     * @return text 
     */
    public function getDataType()
    {
        return $this->data_type;
    }
    
    /**
     * Get data_type
     *
     * @return text 
     */
    public function getSoapMethod()
    {
        return $this->data_type;
    }

    /**
     * Set data_body
     *
     * @param text $data_body
     */
    public function setDataBody($data_body)
    {
        $this->data_body = $data_body;
    }

    /**
     * Get data_body
     *
     * @return text 
     */
    public function getDataBody()
    {
        return $this->data_body;
    }

    /**
     * Set header
     *
     * @param text $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    /**
     * Get header
     *
     * @return text 
     */
    public function getHeader()
    {
        return $this->header;
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
     * Set host
     *
     * @param Host $host
     */
    public function setHost($host) {
        $this->host = $host;
    }
    
    /**
     * Get host
     *
     * @return Host 
     */
    public function getHost() {
        return $this->host;
    }

    //----------------------------------------------
    // Funzioni extra
    //----------------------------------------------
    
    public function getUrl($prod) {
        return ($prod ? $this->getHost()->getHost() : $this->getHost()->getHostDev())."/{$this->getUri()}".($this->getQueryString() ? "?{$this->getQueryString()}" : '');
    }

    public function __toString() {
        return $this->getName();
    }
    
}