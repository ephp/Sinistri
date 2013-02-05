<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Help;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\Bundle\WsInvokerBundle\Entity\Help\Field
 *
 * @ORM\Table(name="ws_help_input_fields")
 * @ORM\Entity(repositoryClass="Ephp\Bundle\WsInvokerBundle\Entity\Help\FieldRepository")
 */
class Field
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
     * @var string $service_name
     *
     * @ORM\Column(name="service_name", type="string", length=64)
     */
    private $service_name;

    /**
     * @var string $order_field
     *
     * @ORM\Column(name="order_field", type="integer")
     */
    private $order_field;

    /**
     * @var string $field
     *
     * @ORM\Column(name="field", type="string", length=64)
     */
    private $field;

    /**
     * @var string $required
     *
     * @ORM\Column(name="required", type="string", length=128, nullable=true)
     */
    private $required;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="Service", inversedBy="fields")
     * @ORM\JoinColumn(name="service_name", referencedColumnName="name")
     */
    private $service;

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
     * Set service_name
     *
     * @param string $serviceName
     */
    public function setServiceName($serviceName)
    {
        $this->service_name = $serviceName;
    }

    /**
     * Get service_name
     *
     * @return string 
     */
    public function getServiceName()
    {
        return $this->service_name;
    }

    /**
     * Set field
     *
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * Get field
     *
     * @return string 
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Set required
     *
     * @param string $required
     */
    public function setRequired($required)
    {
        $this->required = $required;
    }

    /**
     * Get required
     *
     * @return string 
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    public function getService() {
        return $this->service;
    }

    public function setService($service) {
        $this->service = $service;
    }

    public function getOrderField() {
        return $this->order_field;
    }

    public function setOrderField($order_field) {
        $this->order_field = $order_field;
    }

}