<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\User;
use DateTime;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom ne peut pas être vide.',
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 255,
                        'minMessage' => 'Le nom doit avoir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add(
                'niveau',
                ChoiceType::class,
                [
                    'choices' => [
                        'Facile' => 'FACILE',  //choiceType pour statique
                        'Moyenne' => 'MOYEN',
                        'Difficile' => 'DIFFICILE'
                    ],
                    'expanded' => false
                ], //expended list Select  (false) 
                ['multiple' => false] //check box true , radio false
            )
            ->add('datedebut', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date de début est requise.']),
                ],
            ])
            ->add('datefin', DateType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date de début est requise.']),
                    new Callback([$this,'validateDateFin']),
                ],
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide (JPEG, PNG).',
                    ]),
                ],
            ])
            ->add('description')
            ->add('prix', null, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prix ne peut pas être vide.',
                    ]),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'Le prix doit être supérieur à zéro.',
                    ]),
                ],
            ])
            ->add('cat', EntityType::class, [
                'class' => Categorie::class,
                'placeholder' => 'Sélectionner une catégorie',
                'choice_label' => 'nom'
            ])
      /*       ->add('artiste', EntityType::class, [
                'class' => User::class,
                'placeholder' => 'Sélectionner un artiste',
                'choice_label' => 'nom'
            ]) */
            ->add('Ajouter', SubmitType::class);
    }


    public function validateDateFin($value, ExecutionContextInterface $context): void
    {
        $rootForm = $context->getRoot();
        $formation = $rootForm->getData();
    
        if ($formation instanceof Formation) {
            $datedebut = $formation->getDatedebut();
            if ($value <= $datedebut) {
                $context->buildViolation('La date de fin doit être supérieure à la date de début.')
                    ->atPath('datefin')
                    ->addViolation();
            }
        }
    }
    
    


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
