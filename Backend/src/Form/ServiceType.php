<?php

namespace App\Form;

use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ServiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre del servicio',
                'attr' => ['placeholder' => 'Ej: Corte de pelo hombre'],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Descripción',
                'required' => false,
                'attr' => ['rows' => 3, 'placeholder' => 'Descripción del servicio...'],
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Duración (minutos)',
                'attr' => ['placeholder' => '30', 'min' => 5, 'step' => 5],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Precio',
                'currency' => 'EUR',
                'attr' => ['placeholder' => '25.00'],
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Categoría',
                'required' => false,
                'placeholder' => 'Seleccionar categoría',
                'choices' => [
                    'Corte' => 'corte',
                    'Color' => 'color',
                    'Peinado' => 'peinado',
                    'Tratamiento' => 'tratamiento',
                    'Barba' => 'barba',
                    'Otros' => 'otros',
                ],
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Activo',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
