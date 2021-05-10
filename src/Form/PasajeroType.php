<?php

namespace App\Form;

use App\Entity\Pasajero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class PasajeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class)
            //->add('roles')
            ->add('nombre')
            ->add('apellido')
            ->add('dni')
            ->add('nacimiento', DateType::class, 
                ['widget'=>'single_text'])
            //->add()
            ->add('password', PasswordType::class)
            ->add('aceptar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pasajero::class,
        ]);
    }
}
