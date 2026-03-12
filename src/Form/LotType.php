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

class LotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomLot', TextType::class, [
                'label' => 'Nom du lot',
                'constraints' => [new NotBlank()],
                'attr' => ['placeholder' => 'Ex: Chauffage, Plomberie'],
            ])
            ->add('systemes', EntityType::class, [
                'class' => Systeme::class,
                'choice_label' => 'nomSysteme',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Systèmes associés',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Lot::class]);
    }
}
