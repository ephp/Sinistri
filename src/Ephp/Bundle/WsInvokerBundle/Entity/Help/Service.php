<?php

namespace Ephp\Bundle\WsInvokerBundle\Entity\Help;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ephp\Bundle\WsInvokerBundle\Entity\Help\Service
 *
 * @ORM\Table(name="ws_help_service")
 * ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="Ephp\Bundle\WsInvokerBundle\Entity\Help\ServiceRepository")
 */
class Service {

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=64)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    protected $name;
    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    protected $description;
    /**
     * @var text $uri
     *
     * @ORM\Column(name="uri", type="string", length=128)
     */
    protected $uri;
    /**
     * @var text method
     *
     * @ORM\Column(name="method", type="string", length=8)
     */
    protected $method;
    /**
     * @var text $header
     *
     * @ORM\Column(name="header", type="text", nullable=true)
     */
    protected $header;
    /**
     * @var text $example
     *
     * @ORM\Column(name="example", type="text", nullable=true)
     */
    protected $example;
    /**
     * @var text $output
     *
     * @ORM\Column(name="output", type="text", nullable=true)
     */
    protected $output;
    /**
     * @var boolean $enable_log
     *
     * @ORM\Column(name="enable_log", type="boolean", nullable=true)
     */
    private $enable_log;
    /**
     * @ORM\ManyToOne(targetEntity="Ephp\Bundle\WsInvokerBundle\Entity\Config\Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $group;
    /**
     * @ORM\OneToMany(targetEntity="Field", mappedBy="service", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $fields;
    /**
     * @ORM\OneToMany(targetEntity="OutField", mappedBy="service", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected $out_fields;

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    public function setId($name) {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    public function getId() {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set example
     *
     * @param text $example
     */
    public function setExample($example) {
        $this->example = $example;
    }

    /**
     * Get example
     *
     * @return text 
     */
    public function getExample() {
        return $this->example;
    }

    /**
     * Set gruop
     *
     * @param \Ephp\Bundle\WsInvokerBundle\Entity\Config\Group $gruop
     */
    public function setGroup($gruop) {
        $this->group = $gruop;
    }

    /**
     * Get gruop
     *
     * @return \Ephp\Bundle\WsInvokerBundle\Entity\Config\Group 
     */
    public function getGroup() {
        return $this->group;
    }

    public function getUri() {
        return $this->uri;
    }

    public function setUri($uri) {
        $this->uri = $uri;
    }

    public function getMethod() {
        return $this->method;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function getHeader() {
        return $this->header;
    }

    public function setHeader($header) {
        $this->header = $header;
    }

    public function getOutput() {
        return $this->output;
    }

    public function setOutput($output) {
        $this->output = $output;
    }

    public function getFields() {
        return $this->fields;
    }

    public function setFields($fields) {
        $this->fields = $fields;
    }

    public function getOutFields() {
        return $this->out_fields;
    }

    public function setOutFields($out_fields) {
        $this->out_fields = $out_fields;
    }

    /**
     * Get enable_log
     *
     * @return boolean 
     */
    public function getEnableLog() {
        return $this->enable_log;
    }

    /**
     * Set host
     *
     * @param Host $enable_log
     */
    public function setEnableLog($enable_log) {
        $this->enable_log = $enable_log;
    }

    public function __toString() {
        return $this->getName();
    }

    private $_input = array();
    private $_output = array();

    public function depopulate(\Doctrine\ORM\EntityManager $em) {
        try {
            $em->beginTransaction();
            foreach ($this->getFields() as $field) {
                $this->_input[$field->getField()] = $field;
                $this->getFields()->removeElement($field);
            }
            foreach ($this->getOutFields() as $field) {
                $this->_output[$field->getField()] = $field;
                $this->getOutFields()->removeElement($field);
            }
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
    }

    /**
     * ORM\PrePersist()
     * ORM\PreUpdate()
     */
    public function populate() {
        $i = 0;
        $fieldsCollection = new \Doctrine\Common\Collections\ArrayCollection();
        $outFieldsCollection = new \Doctrine\Common\Collections\ArrayCollection();

        $fields = array();
        $out_fields = array();
        preg_match_all('/%[a-z_.\-]+%/', $this->getUri(), $find1);
        $fields = array_merge($fields, $find1[0]);
        preg_match_all('/%[a-z_.\-]+%/', $this->getHeader(), $find2);
        $fields = array_merge($fields, $find2[0]);
        preg_match_all('/%[a-z_.\-]+%/', $this->getExample(), $find3);
        $fields = array_merge($fields, $find3[0]);
        if (count($fields) > 0) {
            foreach ($fields as $field_name) {
                $field = new Field();
                $field->setService($this);
                $field->setOrderField($i++);
                $field->setField($field_name);
                if (isset($this->_input[$field_name])) {
                    $field->setRequired($this->_input[$field_name]->getRequired());
                    $field->setDescription($this->_input[$field_name]->getDescription());
                } else {
                    if ($field_name == '%token%') {
                        $field->setRequired('SI');
                        $field->setDescription("Token restituito dall'autenticazione");
                    }
                }
                $fieldsCollection->add($field);
            }
            $this->setFields($fieldsCollection);
        }
        preg_match_all('/%[a-z_.\-]+%/', $this->getOutput(), $find4);
        $out_fields = array_merge($out_fields, $find4[0]);
        if (count($out_fields) > 0) {
            foreach ($out_fields as $field_name) {
                $field = new OutField();
                $field->setField($field_name);
                $field->setService($this);
                $field->setOrderField($i++);
                if (isset($this->_output[$field_name])) {
                    $field->setRequired($this->_output[$field_name]->getRequired());
                    $field->setDescription($this->_output[$field_name]->getDescription());
                } else {
                    if ($field_name == '%token%') {
                        $field->setRequired('SI');
                        $field->setDescription("Token restituito dall'autenticazione da usare nell'header nelle chiamate future");
                    }
                }
                $outFieldsCollection->add($field);
            }
            $this->setOutFields($outFieldsCollection);
        }
        preg_match_all('/<\/[a-z_.\-]+>[^<]+[.]{3}/', $this->getOutput(), $find5);
        $out_fields = $find5[0];
        if (count($out_fields) > 0) {
            foreach ($out_fields as $field_name) {
                $field_name = trim(str_replace(array('/', '...'), array('', ''), $field_name));
                $field = new OutField();
                $field->setField($field_name);
                $field->setService($this);
                $field->setOrderField($i++);
                if (isset($this->_output[$field_name])) {
                    $field->setRequired($this->_output[$field_name]->getRequired());
                    $field->setDescription($this->_output[$field_name]->getDescription());
                } else {
                    $field->setRequired('Ripetibile');
                    $field->setDescription("il tag {$field_name} si puÃƒÆ’Ã†â€™Ãƒâ€šÃ‚Â² ripetere");
                }
                $outFieldsCollection->add($field);
            }
            $this->setOutFields($outFieldsCollection);
        }
    }

}