<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TesteoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fiebre',CheckboxType::class,[
                'required' => false,
            ])
            ->add('garganta',CheckboxType::class,[
                'required' => false,
            ])
            ->add('respiratoria',CheckboxType::class,[
                'required' => false,
            ])
            ->add('gustoOlfato',CheckboxType::class,[
                'required' => false,
            ])
            ->add('field_name5',CheckboxType::class,[
                'required' => false,
            ])
            ->add('temperatura')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
