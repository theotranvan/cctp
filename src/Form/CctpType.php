<?php
namespace App\Form;

use App\Entity\Cctp;
use App\Entity\Entreprise;
use App\Entity\Lot;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class CctpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du CCTP',
                'constraints' => [new NotBlank()],
                'attr' => ['placeholder' => 'Ex: CCTP Chauffage Collectif'],
            ])
            ->add('nomOperation', TextType::class, [
                'label' => "Nom de l'opération",
                'constraints' => [new NotBlank()],
                'attr' => ['placeholder' => 'Ex: Résidence Les Lilas'],
            ])
            ->add('numAffaire', IntegerType::class, [
                'label' => "Numéro d'affaire",
                'constraints' => [new NotBlank(), new Positive()],
                'attr' => ['placeholder' => 'Ex: 2024001'],
            ])
            ->add('lots', EntityType::class, [
                'class' => Lot::class,
                'choice_label' => 'nomLot',
                'multiple' => true,
                'expanded' => false,
                'label' => 'Lots associés',
                'required' => false,
                'attr' => ['size' => 6, 'class' => 'form-select'],
            ])
            ->add('entreprises', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nomEntreprise',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('e')
                        ->where('e.role != :role')
                        ->setParameter('role', Entreprise::ROLE_INSTALLATEUR)
                        ->orderBy('e.nomEntreprise', 'ASC');
                },
                'multiple' => true,
                'expanded' => false,
                'label' => 'Maîtres d\'ouvrage / Maîtres d\'œuvre',
                'required' => false,
                'attr' => ['size' => 4, 'class' => 'form-select'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Cctp::class]);
    }
}
