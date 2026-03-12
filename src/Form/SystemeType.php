<?php
namespace App\Form;

use App\Entity\Lot;
use App\Entity\Systeme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class SystemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomSysteme', TextType::class, [
                'label' => 'Nom du système',
                'constraints' => [new NotBlank()],
                'attr' => ['placeholder' => 'Ex: Production de chauffage'],
            ])
            ->add('lots', EntityType::class, [
                'class' => Lot::class,
                'choice_label' => 'nomLot',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Lots associés',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Systeme::class]);
    }
}
