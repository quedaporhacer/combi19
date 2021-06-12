<?php

namespace App\Form;

use App\Entity\Viaje;
use App\Form\RutaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salida')
            ->add('llegada')
            ->add('precio')
            ->add('estado')
            ->add('combi')
            ->add('ruta',RutaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Viaje::class,
        ]);
    }
}
