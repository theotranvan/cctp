<?php
namespace App\Form;

use App\Entity\Specification;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class SpecificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque', TextType::class, ['label' => 'Marque', 'required' => false])
            ->add('type', TextType::class, ['label' => 'Type / Référence', 'required' => false])
            ->add('prixUnitaire', MoneyType::class, [
                'label' => 'Prix unitaire HT (€)',
                'currency' => 'EUR',
                'constraints' => [new NotBlank(), new Positive()],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Specification::class]);
    }
}
