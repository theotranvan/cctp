<?php

namespace App\Form;

use App\Entity\Cctp;
use App\Entity\Entreprise;
use App\Entity\Lot;
use App\Entity\Moa;
use App\Entity\Moe;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CctpType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class)
            ->add('NumAffaire', IntegerType::class, [
                'required' => true
            ])
            ->add('nom_operation', TextType::class)
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.role != :role')
                        ->setParameter(':role', "Installateur")
                        ->orderBy('e.nom_entreprise', 'ASC');
                },
                'label' => 'Moe et/ou Moa',
                'choice_label' => 'nom_entreprise',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('lotCctp', EntityType::class, [
                'class' => Lot::class,
                'multiple' => true,
                'expanded' => false,
                'choice_label' => 'nom_lot',
                'attr' => [
                    'class' => 'select-lot'
                ]
            ])
            
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cctp::class,
        ]);
    }
}
