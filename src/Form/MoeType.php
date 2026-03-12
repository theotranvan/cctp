<?php
namespace App\Form;

use App\Entity\Entreprise;
use App\Entity\Moe;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class MoeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomMoe', TextType::class, ['label' => 'Nom', 'constraints' => [new NotBlank()]])
            ->add('prenomMoe', TextType::class, ['label' => 'Prénom', 'constraints' => [new NotBlank()]])
            ->add('telMoe', TextType::class, ['label' => 'Téléphone', 'required' => false])
            ->add('mailMoe', EmailType::class, ['label' => 'Email', 'required' => false])
            ->add('entreprise', EntityType::class, [
                'class' => Entreprise::class,
                'choice_label' => 'nomEntreprise',
                'query_builder' => fn(EntityRepository $er): QueryBuilder => $er->createQueryBuilder('e')
                    ->where('e.role = :r')->setParameter('r', Entreprise::ROLE_MOE)->orderBy('e.nomEntreprise', 'ASC'),
                'label' => 'Entreprise MOE',
                'required' => false,
                'placeholder' => 'Sélectionner',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Moe::class]);
    }
}
