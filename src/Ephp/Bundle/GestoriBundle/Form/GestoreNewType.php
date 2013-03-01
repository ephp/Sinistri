<?php

namespace Ephp\Bundle\GestoriBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GestoreNewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sigla')
            ->add('nome')
            ->add('email')
            ->add('password', 'password')
        ;
    }

    public function getName()
    {
        return 'ephp_bundle_gestoribundle_gestoretype';
    }
}
