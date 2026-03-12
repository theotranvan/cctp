<?php
namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Optionnel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OptionnelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Titre', 'required' => false])
            ->add('content', TextareaType::class, ['label' => 'Contenu', 'constraints' => [new NotBlank()], 'attr' => ['rows' => 5]])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nomCategorie',
                'label' => 'Catégorie',
                'placeholder' => 'Sélectionner une catégorie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Optionnel::class]);
    }
}
