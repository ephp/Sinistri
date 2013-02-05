<?php

namespace Ephp\Bundle\WsInvokerBundle\Form\Help;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('field')
            ->add('required')
            ->add('description')
        ;
    }

    public function getName()
    {
        return 'ephp_bundle_siliconbundle_help_fieldtype';
    }
}
