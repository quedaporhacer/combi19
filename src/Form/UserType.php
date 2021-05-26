<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('nombre')
            ->add('apellido')
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'La contraseña repetida es distinta',
                'first_options'  => array('label' => 'Contraseña'),
                'second_options' => array('label' => 'Repetir Contraseña'),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Elegi una contraseña'
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'La contraseña debe de ser de al menos 6 caracteres'
                    ])
                ]
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
