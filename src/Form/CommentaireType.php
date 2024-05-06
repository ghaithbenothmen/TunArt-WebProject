<?php

// src/Form/CommentaireType.php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import EntityType
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenuc')
            ->add('actualite', EntityType::class, [ // Specify EntityType for actualite
                'class' => 'App\Entity\Actualite',
                'choice_label' => 'titre', // Customize this according to your Actualite entity
            ])
            ->add('user', EntityType::class, [ // Specify EntityType for actualite
                'class' => 'App\Entity\User',
                'choice_label' => 'Prenom', // Customize this according to your Actualite entity
            ])
                    ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}

