<?php

namespace App\Form;

use App\Entity\Chantier;
use App\Entity\Typeusage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChantierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom_chantier', TextType::class)
            ->add('surface', TextType::class)
            ->add('type_lgt', TextType::class, [
                'required' => false
            ])
            ->add('nb_lgt', IntegerType::class, [
                'required' => false
            ])
            ->add('typeusage', EntityType::class, [
                'class' => Typeusage::class,
                'choice_label' => 'nom_usage'
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chantier::class,
        ]);
    }
}
