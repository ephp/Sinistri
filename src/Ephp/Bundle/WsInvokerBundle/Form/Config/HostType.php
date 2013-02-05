<?php

namespace Ephp\Bundle\WsInvokerBundle\Form\Config;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class HostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('host', 'text')
            ->add('host_dev', 'text')
            ->add('note', 'textarea')
            ->add('group')
        ;
    }

    public function getName()
    {
        return 'ephp_bundle_siliconbundle_config_hosttype';
    }
}
