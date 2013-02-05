<?php

namespace Ephp\Bundle\WsInvokerBundle\Form\Config;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GroupType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('note')
        ;
    }

    public function getName()
    {
        return 'ephp_bundle_siliconbundle_config_grouptype';
    }
}
