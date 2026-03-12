<?php

namespace App\Form;

use App\Entity\Lot;
use App\Entity\Produit;
use App\Entity\Specification;
use App\Entity\Systeme;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('nom_lot', EntityType::class, [
                'class' => Lot::class,
                'placeholder' => 'Lot (choisir un lot)',
            ])*/

            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'placeholder' => 'produit (choisir un lot)',
                'attr' => [
                    'class' => 'select-prod'
                ]
            ])
            ->add('types', ChoiceType::class, [
                'placeholder' => 'type (choisir un produit)',
                'required' => false
            ])
            ->add('valider', SubmitType::class)
            ;

            /*$formModifier1 = function (FormInterface $form, Lot $lot = null) {
                //récupération de l'élément séléctionné et appel des spécifications liées
                $produit = null === $lot ? [] : array_unique($lot->getProduits()->toArray());

                //modification du champ types en fonction de l'élément séléctionné
                $form->add('nom_produit', EntityType::class, [
                    'class' => Produit::class,
                    'choices' => $produit,
                    'required' => false,
                    'choice_label' => 'nom_produit',
                    'placeholder' => 'produit (Choisir un lot)',
                    'attr' => ['class' => 'custom-select'],
                    'label' => 'Produit'
                ]);
            };*/
            
            $formModifier = function (FormInterface $form, Produit $produit = null) {
                //récupération de l'élément séléctionné et appel des spécifications liées
                $specification = null === $produit ? [] : array_unique($produit->getSpecifications()->toArray());
                
                //modification du champ types en fonction de l'élément séléctionné
                $form->add('types', EntityType::class, [
                    'class' => Specification::class,
                    'choices' => $specification,
                    'required' => false,
                    'choice_label' => 'type',
                    'placeholder' => 'type (Choisir un produit)',
                    'attr' => ['class' => 'custom-select'],
                    'label' => 'Type'
                ]);
            };
            //écouteur d'événement
            /*$builder->get('nom_lot')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier1) {
                    $lot = $event->getForm()->getData();
                    $formModifier1($event->getForm()->getParent(), $lot);
                }
            );*/

            //écouteur d'événement
            $builder->get('produit')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $produit = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $produit);
                }
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
