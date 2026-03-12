<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Moa;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_moa', TextType::class)
            ->add('prenom_moa', TextType::class)
            ->add('tel_moa', TextType::class)
            ->add('mail_moa', TextType::class)
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Moa::class,
        ]);
    }
}
