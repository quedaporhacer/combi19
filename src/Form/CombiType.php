<?php

namespace App\Form;

use App\Entity\Combi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CombiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('patente')
            ->add('modelo')
            ->add('capacidad')
            ->add('calidad')
            ->add('chofer')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Combi::class,
        ]);
    }
}
