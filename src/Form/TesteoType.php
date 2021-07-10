<?php

namespace App\Form;

use Doctrine\DBAL\Types\FloatType as TypesFloatType;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FloatType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TesteoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fiebre',CheckboxType::class,[
                'required' => false, 
                'label'=>'¿Tuvo fiebre la ultima semana?', 
            ])
            ->add('garganta',CheckboxType::class,[
                'required' => false,
                'label'=>'¿Posee dolor de garganta?',
            ])
            ->add('respiratoria',CheckboxType::class,[
                'required' => false,
                'label'=>'¿Posee alguna afeccion de garganta?',
            ])
            ->add('gustoOlfato',CheckboxType::class,[
                'required' => false,
                'label'=>'¿Tuvo perdida de gusto u olfato?',
            ])
            ->add('temperatura',NumberType::class,[
                'label' => 'Ingrese la temperatura del pasajero',
                'invalid_message' => 'Ingrese una temperatura'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
