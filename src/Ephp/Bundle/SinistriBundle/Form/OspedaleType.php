<?php

namespace Ephp\Bundle\SinistriBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class OspedaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sigla')
            ->add('nome')
        ;
    }

    public function getName()
    {
        return 'ephp_bundle_sinistribundle_ospedaletype';
    }
}
