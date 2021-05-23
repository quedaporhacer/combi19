<?php

namespace App\Form;

use App\Entity\Pasajero;
use App\Form\UserType;
use App\Form\TarjetaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PasajeroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni')
            ->add('membresia')
            ->add('user', UserType::class, [
                'label' => false,
            ])
           // ->add('tarjetas', CollectionType::class,  [ 
            //        'entry_type' => TarjetaType::class, 
            //        'allow_add' => true,
             //       'allow_delete' => true,
             //   ]
                
            //    )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Pasajero::class,
        ]);
    }
}
