<?php

// src/Form/RegisterUserType.php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => false, // Supprime le label
        ])
        ->add('prenom', TextType::class, [
            'label' => false,
        ])
        ->add('email', TextType::class, [
            'label' => false,
        ])
        ->add('mdp', PasswordType::class, [
            'label' => false,
        ])
        ->add('tel', TextType::class, [
            'label' => false,
        ])
        ->add('image', FileType::class, [
            'label' => false,
            'mapped' => false, // This ensures that Symfony does not try to map this field to a property on your entity
            'required' => false, // Make this field optional
        ])
        ->add('roles', ChoiceType::class, [
            'label' => false,
            'choices' => [
                'Artiste' => 'ROLE_ARTISTE',
                'Client' => 'ROLE_CLIENT',
            ],
            'expanded' => false,
            'multiple' => true,
            'attr' => [
                'class' => 'input100',
                'name' => 'role',
            ],
        ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
