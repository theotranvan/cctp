<?php

namespace App\Form;
use App\Entity\Lot;
use App\Entity\Systeme;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SystemeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_systeme', TextType::class)
            ->add('lot', EntityType::class, [
                'class' => Lot::class,
                'multiple' => true,
                'expanded' => true,
                'choice_label' => 'nom_lot',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.nom_lot', 'ASC');
                },
                'label' => 'Lots associés'
            ]

            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Systeme::class,
        ]);
    }
}
