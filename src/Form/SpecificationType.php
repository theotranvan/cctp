<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Specification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SpecificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('marque', TextType::class, [
                'required' => false
            ])
            ->add('type', TextType::class, [
                'required' => false
            ])
            ->add('prix_unitaire', TextType::class)
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'nom_produit',
                
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Specification::class,
        ]);
    }
}
