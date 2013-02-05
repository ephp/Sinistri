<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Log;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthentication
 *
 * @ORM\Table(name="ws_log_authentication", indexes={@ORM\index(name="user_idx", columns={"username"}),@ORM\index(name="expired_idx", columns={"expired_at"}),@ORM\index(name="expired_user_idx", columns={"username","expired_at"})}, uniqueConstraints={@ORM\UniqueConstraint(name="token_idx", columns={"token"})})
 * @ORM\Entity(repositoryClass="Ephp\Bundle\WsInvokerBundle\Entity\Log\LogAuthenticationRepository")
 */
class LogAuthentication
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
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=64)
     */
    private $username;

    /**
     * @var datetime $request_at
     *
     * @ORM\Column(name="request_at", type="datetime")
     */
    private $request_at;

    /**
     * @var string $code
     *
     * @ORM\Column(name="code", type="string", length=3)
     */
    private $code;

    /**
     * @var string $token
     *
     * @ORM\Column(name="token", type="string", length=64, nullable=true)
     */
    private $token;
    
    /**
     * @var string $token
     *
     * @ORM\Column(name="message", type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @var datetime $expired_at
     *
     * @ORM\Column(name="expired_at", type="datetime")
     */
    private $expired_at;

    /**
     * @ORM\OneToMany(targetEntity="LogCall", mappedBy="auth")
     */
    private $calls;

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
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set request_at
     *
     * @param datetime $requestAt
     */
    public function setRequestAt($requestAt)
    {
        $this->request_at = $requestAt;
    }

    /**
     * Get request_at
     *
     * @return datetime 
     */
    public function getRequestAt()
    {
        return $this->request_at;
    }

    /**
     * Set code
     *
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set token
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set message
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set expired_at
     *
     * @param datetime $expiredAt
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expired_at = $expiredAt;
    }

    /**
     * Get expired_at
     *
     * @return datetime 
     */
    public function getExpiredAt()
    {
        return $this->expired_at;
    }
    
    /**
     * Set requests
     *
     * @param array $calls
     */
    public function setCalls($calls)
    {
        $this->calls = $calls;
    }

    /**
     * Get requests
     *
     * @return array 
     */
    public function getCalls()
    {
        return $this->calls;
    }
}