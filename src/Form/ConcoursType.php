<?php

namespace App\Form;

use App\Entity\Concours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConcoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add
            (
                'type',
                ChoiceType::class,
                [
                    'choices' => [
                        'MUSIC' => 'MUSIC',  //choiceType pour statique
                        'PAINTING' => 'PAINTING',
                        'PHOTOGRAPHY' => 'PHOTOGRAPHY',
                        'LITERATURE' => 'LITERATURE',
                        'CINEMA' => 'CINEMA',
                        'GRAFFITI' => 'GRAFFITI',
                        'DJING' => 'DJING',
                        'DESIGN' => 'DESIGN',
                        'VIDEO_EDITING' => 'VIDEO_EDITING'
                    ],
                    'expanded' => false
                ], //expended list Select  (false) 
                ['multiple' => false] //check box true , radio false
            )
            ->add('prix')
            ->add('lien')
            ->add('nom')
            ->add('Maxparticipant')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Concours::class,
        ]);
    }
}
