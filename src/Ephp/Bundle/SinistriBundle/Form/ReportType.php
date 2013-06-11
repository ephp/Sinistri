<?php

namespace Ephp\Bundle\SinistriBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ReportType extends AbstractType {

    private $scheda;
    
    function __construct($scheda) {
        $this->scheda = $scheda;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $scheda = $this->scheda;
        $builder
                ->add('scheda', null, array(
                    'empty_value' => false,
                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) use ($scheda) {
                        return $er->createQueryBuilder('s')->where('s.id = :scheda')->setParameter('scheda', $scheda);
                    }))
                ->add('number', 'hidden')
                ->add('data', null, array(
                    'format' => 'dd-MM-yyyy',
                    ))
                ->add('copertura')
                ->add('stato')
                ->add('descrizione_in_fatto')
                ->add('relazione_avversaria')
                ->add('relazione_ex_adverso')
                ->add('medico_legale1')
                ->add('medico_legale2')
                ->add('medico_legale3')
                //->add('medici_legali')
                ->add('valutazione_responsabilita')
                ->add('analisi_danno')
                ->add('riserva')
                ->add('possibile_rivalsa')
                ->add('azioni')
                ->add('richiesta_sa')
                ->add('note')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Ephp\Bundle\SinistriBundle\Entity\Report'
        ));
    }

    public function getName() {
        return 'report';
    }

}
