<?php

namespace App\Form;

use App\Entity\Combi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CombiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('patente')
            ->add('modelo')
            ->add('capacidad',IntegerType::class,
            ['constraints' => [new Positive()],
                'attr' => [
                'min' => 1]
            ])
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
