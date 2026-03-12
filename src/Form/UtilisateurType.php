<?php
namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'constraints' => [new NotBlank(), new Email()],
            ])
            ->add('nomUser', TextType::class, ['label' => 'Nom', 'constraints' => [new NotBlank()]])
            ->add('prenomUser', TextType::class, ['label' => 'Prénom', 'constraints' => [new NotBlank()]])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rôles',
                'choices' => ['Administrateur' => 'ROLE_ADMIN', 'Utilisateur' => 'ROLE_USER'],
                'multiple' => true,
                'expanded' => true,
            ]);

        if ($options['include_password']) {
            $builder->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => 'Mot de passe',
                'constraints' => [new NotBlank()],
                'attr' => ['autocomplete' => 'new-password'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
            'include_password' => false,
        ]);
    }
}
