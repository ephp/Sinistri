<?php

namespace Ephp\Bundle\SinistriBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StatoOperativoEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('stato')
        ;
    }

    public function getName()
    {
        return 'ephp_bundle_sinistribundle_statooperativotype';
    }
}
