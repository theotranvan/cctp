<?php
namespace App\Form;

use App\Entity\Chantier;
use App\Entity\Devis;
use App\Entity\Entreprise;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('chantier', EntityType::class, [
                'class' => Chantier::class,
                'choice_label' => 'nomChantier',
                'label' => 'Chantier',
                'placeholder' => 'Sélectionner un chantier',
            ])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nomEntreprise',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('e')
                        ->where('e.role = :role')
                        ->setParameter('role', Entreprise::ROLE_INSTALLATEUR)
                        ->orderBy('e.nomEntreprise', 'ASC');
                },
                'label' => 'Installateur',
                'placeholder' => 'Sélectionner un installateur',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Devis::class]);
    }
}
