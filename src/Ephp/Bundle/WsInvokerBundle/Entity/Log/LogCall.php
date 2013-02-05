<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Log;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\Bundle\WsInvokerBundle\Entity\Log\LogCall
 *
 * @ORM\Table(name="ws_log_calls")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\WsInvokerBundle\Entity\Log\LogCallRepository")
 */
class LogCall {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer $id
     */
    private $auth_id;

    /**
     * @var integer $id
     */
    private $service_id;

    /**
     * @var text $request
     *
     * @ORM\Column(name="request", type="text", nullable=true)
     */
    private $request;

    /**
     * @var text $response
     *
     * @ORM\Column(name="response", type="text", nullable=true)
     */
    private $response;

    /**
     * @var datetime $sended_at
     *
     * @ORM\Column(name="sended_at", type="datetime")
     */
    private $sended_at;

    /**
     * @var integer $duration
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\ManyToOne(targetEntity="LogAuthentication", inversedBy="calls")
     * @ORM\JoinColumn(name="auth_id", referencedColumnName="id")
     */
    private $auth;

    /**
     * @ORM\ManyToOne(targetEntity="Ephp\Bundle\WsInvokerBundle\Entity\Help\Service")
     * @ORM\JoinColumn(name="service_id", referencedColumnName="name")
     */
    private $service;

    /**
     * @ORM\OneToMany(targetEntity="LogRequest", mappedBy="call")
     */
    private $requests;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set auth_id
     *
     * @param integer $authId
     */
    public function setAuthId($authId) {
        $this->auth_id = $authId;
    }

    /**
     * Get auth_id
     *
     * @return integer 
     */
    public function getAuthId() {
        return $this->auth_id;
    }

    /**
     * Set auth_id
     *
     * @param integer $service_id
     */
    public function setServiceId($service_id) {
        $this->service_id = $service_id;
    }

    /**
     * Get auth_id
     *
     * @return integer 
     */
    public function getServiceId() {
        return $this->service_id;
    }

    /**
     * Set request
     *
     * @param text $request
     */
    public function setRequest($request) {
        if(is_array($request) || $request instanceof \stdClass) {
            $this->request = json_encode($request);
        } else {
            $this->request = $request;
        }
    }

    /**
     * Get request
     *
     * @return text 
     */
    public function getRequest() {
        return $this->request;
    }

    /**
     * Set response
     *
     * @param text $response
     */
    public function setResponse($response) {
        if(is_array($response) || $response instanceof \stdClass) {
            $this->response = json_encode($response);
        } else {
            $this->response = $response;
        }
    }

    /**
     * Get response
     *
     * @return text 
     */
    public function getResponse() {
        return $this->response;
    }

    /**
     * Set sended_at
     *
     * @param datetime $sendedAt
     */
    public function setSendedAt($sendedAt) {
        $this->sended_at = $sendedAt;
    }

    /**
     * Get sended_at
     *
     * @return datetime 
     */
    public function getSendedAt() {
        return $this->sended_at;
    }

    /**
     * Set duration
     *
     * @param integer $duration
     */
    public function setDuration($duration) {
        $this->duration = $duration;
    }

    /**
     * Get duration
     *
     * @return integer 
     */
    public function getDuration() {
        return $this->duration;
    }

    /**
     * Set auth
     *
     * @param LogAuthentication $auth
     */
    public function setAuth($auth) {
        $this->auth = $auth;
    }

    /**
     * Get response
     *
     * @return LogResponse 
     */
    public function getAuth() {
        return $this->auth;
    }

    /**
     * Set auth
     *
     * @param LogAuthentication $service
     */
    public function setService($service) {
        $this->service = $service;
    }

    /**
     * Get response
     *
     * @return Ephp\Bundle\WsInvokerBundle\Entity\Help\Service 
     */
    public function getService() {
        return $this->service;
    }

    /**
     * Set requests
     *
     * @param array $requests
     */
    public function setRequests($requests) {
        $this->requests = $requests;
    }

    /**
     * Get requests
     *
     * @return array 
     */
    public function getRequests() {
        return $this->requests;
    }

}