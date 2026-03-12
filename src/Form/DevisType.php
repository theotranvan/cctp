<?php

namespace App\Form;

use App\Entity\Chantier;
use App\Entity\Devis;
use App\Entity\Entreprise;
use App\Entity\Installeur;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\EntityRepositoryGenerator;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('chantier', EntityType::class,[
                'class' => Chantier::class
            ])
            
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.role = :role')
                        ->setParameter(':role', "Installateur");
                },
                'label' => 'Installateur',
                'choice_label' => 'nom_entreprise',
                'multiple' => false,
                'expanded' => true,
            ])
            ->add('fichier', FileType::class, [
                'mapped' => false,
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
