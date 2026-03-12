<?php

namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_entreprise', TextType::class)
            ->add('num_rue_entreprise', TextType::class)
            ->add('nom_rue_entreprise', TextType::class)
            ->add('cp_entreprise', TextType::class)
            ->add('ville_entreprise', TextType::class)
            ->add('role', ChoiceType::class, [
                'choices' => [
                    'MOA' => 'MOA',
                    'MOE' => 'MOE',
                    'Installateur' => 'Installateur'
                ],
                'label' => 'Fonction'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Entreprise::class,
        ]);
    }
}
