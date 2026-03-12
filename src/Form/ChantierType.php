<?php
namespace App\Form;

use App\Entity\Chantier;
use App\Entity\Typeusage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChantierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomChantier', TextType::class, ['label' => 'Nom du chantier', 'constraints' => [new NotBlank()]])
            ->add('surface', NumberType::class, ['label' => 'Surface (m²)', 'required' => false, 'scale' => 2])
            ->add('typeLgt', TextType::class, ['label' => 'Type de logement', 'required' => false, 'attr' => ['placeholder' => 'Ex: Collectif, Individuel']])
            ->add('nbLgt', IntegerType::class, ['label' => 'Nombre de logements', 'required' => false])
            ->add('typeusage', EntityType::class, [
                'class' => Typeusage::class,
                'choice_label' => 'nomUsage',
                'label' => "Type d'usage",
                'placeholder' => 'Sélectionner un type d\'usage',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Chantier::class]);
    }
}
