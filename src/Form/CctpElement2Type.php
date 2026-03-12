<?php

namespace App\Form;

use App\Entity\Cctp;
use App\Entity\Produit;
use App\Entity\Systeme;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CctpElement2Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                //'choices' => $produits,
                /*'query_bulider' => function(EntityRepository $er){
                    return $er->createQueryBuider('p')
                    ->
                },*/
                'multiple' => true,
                'expanded' => true,
                'placeholder' => 'produits (choisir un systeme)',
                'label' => 'Produits',
                'by_reference' => false             
            ])
            
            ->add('Valider', SubmitType::class)
            ;
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cctp::class,
        ]);
    }
}
