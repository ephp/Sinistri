<?php

namespace Ephp\Bundle\GestoriBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class GestoreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sigla')
            ->add('nome')
            ->add('email')
        ;
    }

    public function getName()
    {
        return 'ephp_bundle_gestoribundle_gestoretype';
    }
}
