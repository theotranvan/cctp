<?php

namespace App\Form;

use App\Entity\Catusage;
use App\Entity\Typeusage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CatusageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_catusage', EntityType::class, [
                'class' => Catusage::class,
                'label' => 'Catégorie Usage',
                'mapped' => false,
                'placeholder' => 'Catégorie Usage (choisir une catégorie)'
            ])
            ->add('usage', ChoiceType::class, [
                'placeholder' => 'Choisissez un usage (choisir une catégorie)',
                'required' => false,
                'mapped' => false
            ])
            ->add('valider', SubmitType::class)
        ;

        $formModifier = function(FormInterface $form, Catusage $catUsage = null){
            $usage = null === $catUsage ? [] : $catUsage->getTypeusages();

            $form->add('usage', EntityType::class, [
                'class' => Typeusage::class,
                'choices' => $usage,
                'required' => false,
                'choice_label' => 'nom_usage',
                'placeholder' => 'Choisissez un usage (choisir une catégorie)',
                
            ]);
        };
            $builder->get('nom_catusage')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $catUsage = $event->getForm()->getData();
                    //dd($catUsage);
                    $formModifier($event->getForm()->getParent(), $catUsage);
                }
            );
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
