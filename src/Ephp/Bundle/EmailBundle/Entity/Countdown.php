<?php

namespace Ephp\Bundle\EmailBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Countdown
 *
 * @ORM\Table(name="sx_countdown")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\EmailBundle\Entity\CountdownRepository")
 */
class Countdown
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
     * @var \Ephp\ImapBundle\Entity\Header
     * 
     * @ORM\ManyToOne(targetEntity="Ephp\ImapBundle\Entity\Header")
     * @ORM\JoinColumn(name="email_id", referencedColumnName="id")
     */
    private $email;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sended_at", type="datetime")
     */
    private $sended_at;

    /**
     * @var \Ephp\Bundle\GestoriBundle\Entity\Gestore
     * 
     * @ORM\ManyToOne(targetEntity="Ephp\Bundle\GestoriBundle\Entity\Gestore")
     * @ORM\JoinColumn(name="gestore_id", referencedColumnName="id", nullable=true)
     */
    private $gestore;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="responsed_at", type="datetime", nullable=true)
     */
    private $responsed_at;

    /**
     * @var \Ephp\ImapBundle\Entity\Header
     * 
     * @ORM\ManyToOne(targetEntity="Ephp\ImapBundle\Entity\Header", inversedBy="body")
     * @ORM\JoinColumn(name="risposta_id", referencedColumnName="id", nullable=true)
     */
    private $risposta;

    /**
     * @var \Ephp\Bundle\SinistriBundle\Entity\Scheda
     * 
     * @ORM\ManyToOne(targetEntity="Ephp\Bundle\SinistriBundle\Entity\Scheda")
     * @ORM\JoinColumn(name="scheda_id", referencedColumnName="id", nullable=true)
     */
    private $scheda;

    /**
     * @var Countdown
     * 
     * @ORM\ManyToOne(targetEntity="Countdown")
     * @ORM\JoinColumn(name="countdown_id", referencedColumnName="id", nullable=true)
     */
    private $countdown;

    /**
     * @var string
     *
     * @ORM\Column(name="stato", type="string", length=1)
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
     * Set sended_at
     *
     * @param \DateTime $sendedAt
     * @return Countdown
     */
    public function setSendedAt($sendedAt)
    {
        $this->sended_at = $sendedAt;
    
        return $this;
    }

    /**
     * Get sended_at
     *
     * @return \DateTime 
     */
    public function getSendedAt()
    {
        return $this->sended_at;
    }

    /**
     * Set responsed_at
     *
     * @param \DateTime $responsedAt
     * @return Countdown
     */
    public function setResponsedAt($responsedAt)
    {
        $this->responsed_at = $responsedAt;
    
        return $this;
    }

    /**
     * Get responsed_at
     *
     * @return \DateTime 
     */
    public function getResponsedAt()
    {
        return $this->responsed_at;
    }

    /**
     * Set risposta
     *
     * @param integer $risposta
     * @return Countdown
     */
    public function setRisposta($risposta)
    {
        $this->risposta = $risposta;
    
        return $this;
    }

    /**
     * Get risposta
     *
     * @return integer 
     */
    public function getRisposta()
    {
        return $this->risposta;
    }

    /**
     * Set email
     *
     * @param \Ephp\ImapBundle\Entity\Header $email
     * @return Countdown
     */
    public function setEmail(\Ephp\ImapBundle\Entity\Header $email = null)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return \Ephp\ImapBundle\Entity\Header 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set gestore
     *
     * @param \Ephp\Bundle\GestoriBundle\Entity\Gestore $gestore
     * @return Countdown
     */
    public function setGestore(\Ephp\Bundle\GestoriBundle\Entity\Gestore $gestore = null)
    {
        $this->gestore = $gestore;
    
        return $this;
    }

    /**
     * Get gestore
     *
     * @return \Ephp\Bundle\GestoriBundle\Entity\Gestore 
     */
    public function getGestore()
    {
        return $this->gestore;
    }

    /**
     * Set scheda
     *
     * @param \Ephp\Bundle\SinistriBundle\Entity\Scheda $scheda
     * @return Countdown
     */
    public function setScheda(\Ephp\Bundle\SinistriBundle\Entity\Scheda $scheda = null)
    {
        $this->scheda = $scheda;
    
        return $this;
    }

    /**
     * Get scheda
     *
     * @return \Ephp\Bundle\SinistriBundle\Entity\Scheda 
     */
    public function getScheda()
    {
        return $this->scheda;
    }

    /**
     * Set countdown
     *
     * @param \Ephp\Bundle\EmailBundle\Entity\Countdown $countdown
     * @return Countdown
     */
    public function setCountdown(\Ephp\Bundle\EmailBundle\Entity\Countdown $countdown = null)
    {
        $this->countdown = $countdown;
    
        return $this;
    }

    /**
     * Get countdown
     *
     * @return \Ephp\Bundle\EmailBundle\Entity\Countdown 
     */
    public function getCountdown()
    {
        return $this->countdown;
    }

    /**
     * Set stato
     *
     * @param string $stato
     * @return Countdown
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
    
    public function getGiorniRimanenti() {
        if($this->responsed_at) {
            $this->responsed_at->setTime(0, 0, 0);
            $this->sended_at->setTime(0, 0, 0);
            return $this->responsed_at->diff($this->sended_at, true)->format('%a');
        } else {
            $oggi = new \DateTime();
            $oggi->setTime(0, 0, 0);
            $this->sended_at->setTime(0, 0, 0);
            $scadenza = new \DateTime();
            $scadenza->setTimestamp($this->sended_at->getTimestamp());
            $scadenza->add(new \DateInterval('P15D'));
            return $scadenza->diff($oggi, false)->format('%r%a');
        }
    }
}