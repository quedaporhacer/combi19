<?php

namespace App\Form;

use App\Entity\Viaje;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class ViajeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salida', DateTimeType::class,
                ['date_widget' => 'single_text','time_widget' => 'single_text'] )
            //->add('llegada', DateTimeType::class,
            //['date_widget' => 'single_text','time_widget' => 'single_text'] )
            ->add('combi')
            ->add('ruta')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Viaje::class,
        ]);
    }
}
