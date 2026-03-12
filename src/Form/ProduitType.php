<?php

namespace App\Form;

use App\Entity\Lot;
use App\Entity\Produit;
use App\Entity\Systeme;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_produit', TextType::class)
            ->add('unite', TextType::class)
            ->add('title', TextType::class)
            ->add('content', CKEditorType::class)
            ->add('modifiable', CKEditorType::class)
            ->add('lot', EntityType::class, [
                'class' => Lot::class,
                'choice_label' => 'nom_lot'
            ])
            ->add('systeme', EntityType::class, [
                'class' => Systeme::class,
                'choice_label' => 'nom_systeme',
                'placeholder' => '(choisissez un système)',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
