<?php

namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Moe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_moe', TextType::class)
            ->add('prenom_moe', TextType::class)
            ->add('tel_moe', TextType::class)
            ->add('mail_moe', TextType::class)
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Moe::class,
        ]);
    }
}
