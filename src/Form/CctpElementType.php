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

class CctpElementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
            ->add('systeme', EntityType::class,[
                'class' => Systeme::class,
                'required' => false,
                'mapped' => false,
                'placeholder' => 'Choisir un système'             
            ])
            ->add('produits', ChoiceType::class, [
                'placeholder' => 'elements (Choisir un système)',
                'mapped' => false
            ])
            ->add('Valider', SubmitType::class)
            ;
            
            $formModifier = function(FormInterface $form, Systeme $systeme){
                //récupération du système séléctionné et appel des éléments liés
                $produits =  $systeme->getProduits();
                //modification du champ produits en fonction du systeme séléctionné
                $form->add('produits', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $produits,
                    'multiple' => true,
                    'expanded' => true,
                    'placeholder' => 'produits (choisir un systeme)',
                    'label' => 'Produits',
                    'by_reference' => false
                ]);
            };
            //écouteur d'évenement
            $builder->get('systeme')->addEventListener(
                FormEvents::POST_SUBMIT,
                function(FormEvent $event) use ($formModifier){
                    $systeme = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $systeme);
                }
            );
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cctp::class,
        ]);
    }
}
