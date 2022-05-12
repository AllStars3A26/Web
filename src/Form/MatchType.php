<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Matchh;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MatchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

//            ->add('resultatMatch',EntityType::class,[
//                'class'=>Equipe::class,
//                'choice_label'=>'id_equipe',
//                'multiple'=>false,
//                'expanded'=>false,
//            ])
//            ->add('idEquipe1',EntityType::class,[
//                'class'=>Equipe::class,
//                'choice_label'=>'id_equipe',
//                'multiple'=>false,
//                'expanded'=>false,
//            ])
//            ->add('idEquipe2',EntityType::class,[
//                'class'=>Equipe::class,
//                'choice_label'=>'id_equipe',
//                'multiple'=>false,
//                'expanded'=>false,
//            ])
//            ->add('dateMatch')
//            ->add('save', SubmitType::class)
            ->add('resultatMatch')
            ->add('idEquipe1')
            ->add('idEquipe2')
            ->add('dateMatch', DateType::class, array(
                'required' => false,
                'widget' => 'single_text'
))
            ->add('idTournoi')
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matchh::class,
        ]);
    }
}
