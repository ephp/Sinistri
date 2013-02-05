<?php

namespace Ephp\Bundle\WsInvokerBundle\Form\Config;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('method', 'choice', array('choices' => array('GET' => 'GET', 'POST' => 'POST', 'PUT' => 'PUT', 'SOAP' => 'SOAP')))
            ->add('uri')
            ->add('query_string')
            ->add('data_type', null, array('label' => 'Data Type/Soap Method'))
            ->add('header')
            ->add('data_body')
            ->add('host')
        ;
    }

    public function getName()
    {
        return 'ephp_bundle_siliconbundle_config_servicetype';
    }
}
