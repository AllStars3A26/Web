<?php

namespace App\Form;

use App\Entity\Cours;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;

class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('nome')
            ->add('type', ChoiceType::class, [
                'choices'  => [
                    'RPM' => 'RPM',
                    'Body Pump' => 'Body Pump',
                    'Yoga' => 'Yoga',
                    'Boxe' => 'Boxe',
                    'Musculation' => 'Musculation',
                    'Pilates' => 'Pilates',
                    'Zumba' => 'Zumba'
                ],
            ])
            ->add('imc', ChoiceType::class, [
                'choices'  => [
                    'Endurance' => 'Endurance',
                    'Souplesse' => 'Souplesse',
                    'Résistance' => 'Résistance',
                    'Equilibre' => 'Equilibre',
                    'Vitesse' => 'Vitesse'
                ],
            ])
            ->add('nbHeure')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
