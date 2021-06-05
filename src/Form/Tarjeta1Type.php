<?php

namespace App\Form;

use App\Entity\Tarjeta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class Tarjeta1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero')
            ->add('codigo')
            ->add('vencimiento', DateType::class, [
                'widget' => 'single_text',
                //'format' => 'MM-yyyy',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tarjeta::class,
        ]);
    }
}
