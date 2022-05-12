<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Terrain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class TerrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomTerrain')
            ->add('TypeTerrain',ChoiceType::class,[
                'choices' => [
                    'football' => 'football',
                    "basketball" => "basketball"
                ]
                ])
            ->add('DescriptionTerrain')
            ->add('AdresseTerrain')
            ->add('Disponibilite',ChoiceType::class,[
                'choices' => [
                    '0' => 0,
                     "1" => 1
                ]
                ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terrain::class,
        ]);
    }
}
