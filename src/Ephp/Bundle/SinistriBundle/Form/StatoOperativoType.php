<?php

namespace Ephp\Bundle\SinistriBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StatoOperativoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stato')
            ->add('tab')
            ->add('stats')
            ->add('primo')
        ;
    }

    public function getName()
    {
        return 'ephp_bundle_sinistribundle_statooperativotype';
    }
}
