<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Log;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\Bundle\WsInvokerBundle\Entity\Log\Response
 *
 * @ORM\Table(name="ws_log_response")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\WsInvokerBundle\Entity\Log\LogResponseRepository")
 */
class LogResponse
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
     * @var integer $request_id
     */
    private $request_id;

    /**
     * @var integer $status_code
     *
     * @ORM\Column(name="status_code", type="integer")
     */
    private $status_code;

    /**
     * @var decimal $time
     *
     * @ORM\Column(name="time", type="decimal", precision=6, scale=3)
     */
    private $time;

    /**
     * @var text $header
     *
     * @ORM\Column(name="header", type="text")
     */
    private $header;

    /**
     * @var text $xml
     *
     * @ORM\Column(name="xml", type="text")
     */
    private $xml;

    /**
     * @var datetime $received_at
     *
     * @ORM\Column(name="received_at", type="datetime")
     */
    private $received_at;

    /**
     * @ORM\OneToOne(targetEntity="LogRequest", inversedBy="response")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id")
     */
    private $request;
    
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
     * Set request_id
     *
     * @param integer $requestId
     */
    public function setRequestId($requestId)
    {
        $this->request_id = $requestId;
    }

    /**
     * Get request_id
     *
     * @return integer 
     */
    public function getRequestId()
    {
        return $this->request_id;
    }

    /**
     * Set status_code
     *
     * @param integer $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->status_code = $statusCode;
    }

    /**
     * Get status_code
     *
     * @return integer 
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * Set time
     *
     * @param decimal $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Get time
     *
     * @return decimal 
     */
    public function getTime()
    {
        return $this->time;
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
     * Set xml
     *
     * @param text $xml
     */
    public function setXml($xml)
    {
        $this->xml = trim($xml);
    }

    /**
     * Get xml
     *
     * @return text 
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * Set received_at
     *
     * @param datetime $receivedAt
     */
    public function setReceivedAt($receivedAt)
    {
        $this->received_at = $receivedAt;
    }

    /**
     * Get received_at
     *
     * @return datetime 
     */
    public function getReceivedAt()
    {
        return $this->received_at;
    }

    /**
     * Set request
     *
     * @param LogRequest $request
     */
    public function setRequest($request) {
        $this->request = $request;
    }

    /**
     * Get request
     *
     * @return LogRequest 
     */
    public function getRequest() {
        return $this->request;
    }

}