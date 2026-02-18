<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nombre',
                'attr' => ['placeholder' => 'Tu nombre'],
            ])
            ->add('surname', TextType::class, [
                'label' => 'Apellidos',
                'required' => false,
                'attr' => ['placeholder' => 'Tus apellidos'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['placeholder' => 'tu@email.com'],
            ])
            ->add('phone', TelType::class, [
                'label' => 'Teléfono',
                'required' => false,
                'attr' => ['placeholder' => '612 345 678'],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'Contraseña',
                    'attr' => ['placeholder' => 'Mínimo 6 caracteres'],
                ],
                'second_options' => [
                    'label' => 'Repetir contraseña',
                    'attr' => ['placeholder' => 'Repite tu contraseña'],
                ],
                'constraints' => [
                    new NotBlank(message: 'Introduce una contraseña'),
                    new Length(
                        min: 6,
                        minMessage: 'La contraseña debe tener al menos {{ limit }} caracteres',
                        max: 4096,
                    ),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
