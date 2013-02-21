<?php

namespace Ephp\Bundle\SinistriBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Link
 *
 * @ORM\Table(name="sx_links")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\SinistriBundle\Entity\LinkRepository")
 */
class Link
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
     * @ORM\ManyToOne(targetEntity="Scheda")
     * @ORM\JoinColumn(name="scheda_id", referencedColumnName="id")
     */
    private $scheda;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="sito", type="string", length=255)
     */
    private $sito;


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
     * Set scheda
     *
     * @param string $scheda
     * @return Link
     */
    public function setScheda($scheda)
    {
        $this->scheda = $scheda;

        return $this;
    }

    /**
     * Get scheda
     *
     * @return string
     */
    public function getScheda()
    {
        return $this->scheda;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Link
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
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
     * Set sito
     *
     * @param string $sito
     * @return Link
     */
    public function setSito($sito)
    {
        $this->sito = $sito;

        return $this;
    }

    /**
     * Get sito
     *
     * @return string
     */
    public function getSito()
    {
        return $this->sito;
    }
}