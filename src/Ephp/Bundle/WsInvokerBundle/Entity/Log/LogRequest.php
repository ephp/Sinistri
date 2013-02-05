<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Log;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\Bundle\WsInvokerBundle\Entity\Log\Request
 *
 * @ORM\Table(name="ws_log_request")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\WsInvokerBundle\Entity\Log\LogRequestRepository")
 */
class LogRequest
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
     * @var integer $call_id
     */
    private $call_id;
    
    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string $method
     *
     * @ORM\Column(name="method", type="string", length=7)
     */
    private $method;
    
    /**
     * @var text $xml
     *
     * @ORM\Column(name="xml", type="text")
     */
    private $xml;

    /**
     * @var datetime $sended_at
     *
     * @ORM\Column(name="sended_at", type="datetime")
     */
    private $sended_at;

    /**
     * @ORM\OneToOne(targetEntity="LogResponse", mappedBy="request")
     */
    private $response;
 
    /**
     * @ORM\ManyToOne(targetEntity="LogCall", inversedBy="requests")
     * @ORM\JoinColumn(name="call_id", referencedColumnName="id")
     */
    private $call;

    /**
     * @var text $header
     *
     * @ORM\Column(name="header", type="text")
     */
    private $header;

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
     * Set auth_id
     *
     * @param integer $auth_id
     */
    public function setAuthId($auth_id)
    {
        $this->call_id = $auth_id;
    }

    /**
     * Get auth_id
     *
     * @return integer 
     */
    public function getAuthId()
    {
        return $this->call_id;
    }

    /**
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
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
     * Set xml
     *
     * @param text $xml
     */
    public function setXml($xml)
    {
        $this->xml = $xml;
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
     * Set sended_at
     *
     * @param datetime $sendedAt
     */
    public function setSendedAt($sendedAt)
    {
        $this->sended_at = $sendedAt;
    }

    /**
     * Get sended_at
     *
     * @return datetime 
     */
    public function getSendedAt()
    {
        return $this->sended_at;
    }

    /**
     * Set response
     *
     * @param LogResponse $response
     */
    public function setResponse($response) {
        $this->response = $response;
    }

    /**
     * Get response
     *
     * @return LogResponse 
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * Set auth
     *
     * @param LogAuthentication $call
     */
    public function setCall($call) {
        $this->call = $call;
    }

    /**
     * Get response
     *
     * @return LogResponse 
     */
    public function getCall() {
        return $this->call;
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


}