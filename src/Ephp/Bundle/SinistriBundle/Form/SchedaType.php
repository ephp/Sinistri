<?php

namespace Ephp\Bundle\SinistriBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SchedaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('anno')
//            ->add('dasc')
//            ->add('tpa')
            ->add('claimant', null, array('required' => false))
            ->add('soi', null, array('required' => false, 'attr' => array('style' => 'width: 30px;', 'maxlength' => '2')))
//            ->add('first_reserve')
//            ->add('amount_reserved')
//            ->add('franchigia')
//            ->add('sa')
//            ->add('offerta_nostra')
//            ->add('offerta_loro')
//            ->add('recupero_offerta_nostra')
//            ->add('recupero_offerta_loro')
//            ->add('note')
//            ->add('prima_pagina')
//            ->add('ospedale')
//            ->add('gestore')
            ->add('giudiziale', 'choices', array('choices' => array('' => '-', '-' => 'Non Giudiziali', '*' => 'Tutti i Gidiziali', 'J' => 'J', 'Y' => 'Y', 'A' => 'A', 'C' => 'C')))
            ->add('stato', null, array('required' => false))
            ->add('priorita')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ephp\Bundle\SinistriBundle\Entity\Scheda'
        ));
    }

    public function getName()
    {
        return 'ephp_bundle_sinistribundle_schedatype';
    }
}
