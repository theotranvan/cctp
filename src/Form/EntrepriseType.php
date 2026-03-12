<?php
namespace App\Form;

use App\Entity\Entreprise;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomEntreprise', TextType::class, ['label' => 'Raison sociale', 'constraints' => [new NotBlank()]])
            ->add('numRueEntreprise', TextType::class, ['label' => 'N° de rue', 'constraints' => [new NotBlank()]])
            ->add('nomRueEntreprise', TextType::class, ['label' => 'Nom de la rue', 'constraints' => [new NotBlank()]])
            ->add('cpEntreprise', TextType::class, [
                'label' => 'Code postal',
                'constraints' => [new NotBlank(), new Length(['min' => 5, 'max' => 5])],
                'attr' => ['placeholder' => '75000'],
            ])
            ->add('villeEntreprise', TextType::class, ['label' => 'Ville', 'constraints' => [new NotBlank()]])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Maître d\'Ouvrage (MOA)' => Entreprise::ROLE_MOA,
                    'Maître d\'Œuvre (MOE)' => Entreprise::ROLE_MOE,
                    'Installateur' => Entreprise::ROLE_INSTALLATEUR,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Entreprise::class]);
    }
}
