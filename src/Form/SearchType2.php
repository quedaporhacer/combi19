<?php

namespace App\Form;

use App\Entity\Ruta;
use App\Form\RutaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SearchType2 extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('origen')
            ->add('destino')
            ->add('salida', DateType::class, 
            ['widget' => 'single_text',
             'mapped' => false
             //'model_timezone' => 'America/Buenos_Aires'
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ruta::class,
        ]);
    }
}
