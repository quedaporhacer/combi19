<?php

namespace App\Form;

use App\Entity\Ruta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Positive;

class RutaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('origen')
            ->add('destino')
            ->add('kilometros', IntegerType::class,
            ['constraints' => [new Positive()],
                'attr' => [
                'min' => 1]
            ])
            ->add('descripcion')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ruta::class,
        ]);
    }
}
