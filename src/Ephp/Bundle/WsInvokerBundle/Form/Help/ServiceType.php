<?php

namespace Ephp\Bundle\WsInvokerBundle\Form\Help;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('group')
            ->add('id')
            ->add('description')
            ->add('uri')
            ->add('method', 'choice', array('choices' => array('GET' => 'GET', 'POST' => 'POST', 'PUT' => 'PUT', 'SOAP' => 'SOAP')))
            ->add('header')
            ->add('example')
            ->add('output')
        ;
    }

    public function getName()
    {
        return 'ephp_bundle_siliconbundle_help_servicetype';
    }
}
