<?php

namespace App\Form;

use App\Entity\Tarjeta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class Tarjeta1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero',NumberType::class,[
                'attr' => ['maxlength' => 16]])
            ->add('codigo',NumberType::class,[
                'attr' => ['maxlength' => 3]])
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
