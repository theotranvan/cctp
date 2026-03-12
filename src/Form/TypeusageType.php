<?php
namespace App\Form;

use App\Entity\Catusage;
use App\Entity\Typeusage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TypeusageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomUsage', TextType::class, ['label' => "Nom de l'usage", 'constraints' => [new NotBlank()]])
            ->add('color', ColorType::class, ['label' => 'Couleur', 'required' => false])
            ->add('catusage', EntityType::class, [
                'class' => Catusage::class,
                'choice_label' => 'nomCatusage',
                'label' => 'Catégorie d\'usage',
                'placeholder' => 'Sélectionner',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Typeusage::class]);
    }
}
