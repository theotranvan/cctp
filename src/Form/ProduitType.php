<?php
namespace App\Form;

use App\Entity\Lot;
use App\Entity\Produit;
use App\Entity\Systeme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProduit', TextType::class, [
                'label' => 'Nom du produit',
                'constraints' => [new NotBlank()],
                'attr' => ['placeholder' => 'Ex: Chaudière gaz murale'],
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre (pour le document Word)',
                'required' => false,
                'attr' => ['placeholder' => 'Titre affiché dans le CCTP'],
            ])
            ->add('unite', TextType::class, [
                'label' => 'Unité',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: U, m², ml'],
            ])
            ->add('lot', EntityType::class, [
                'class' => Lot::class,
                'choice_label' => 'nomLot',
                'label' => 'Lot',
                'placeholder' => 'Sélectionner un lot',
            ])
            ->add('systeme', EntityType::class, [
                'class' => Systeme::class,
                'choice_label' => 'nomSysteme',
                'label' => 'Système',
                'required' => false,
                'placeholder' => 'Aucun système',
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu technique',
                'constraints' => [new NotBlank()],
                'attr' => ['rows' => 10, 'class' => 'form-control', 'placeholder' => 'Description technique du produit...'],
            ])
            ->add('modifiable', TextareaType::class, [
                'label' => 'Contenu modifiable (partie personnalisable)',
                'required' => false,
                'attr' => ['rows' => 5, 'class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Produit::class]);
    }
}
