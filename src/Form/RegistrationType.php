<?php

namespace App\Form;

use Assert\Length;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullName', TextType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'minlength' =>'2',
                    'maxlength' => '50',
                ],
                'label' => 'Nom / Prénom',
                'label_attr ' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min'=>2, 'max'=>50])
                ]
            ])
            ->add('pseudo', TextType::class, [
                'attr' =>[
                    'class' => 'form-control',
                    'minlength' =>'2',
                    'maxlength' => '50',
                ],
                'label' => 'Pseudo (facultatif)',
                'label_attr ' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\Length(['min'=>2, 'max'=>50])
                ]
            ])
            ->add('email', EmailType::class,[
                'attr' =>[
                    'class' => 'form-control',
                    'minlength' =>'2',
                    'maxlength' => '180',
                ],
                'label' => 'Adresse Email',
                'label_attr ' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Email(),
                    new Assert\Length(['min'=>2, 'max'=>180])
                ]
            ])
            ->add('password', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options' =>[
                    'label' => 'Mot de passe',
                ],
                'second_options'=>[
                    'label'=>'Confirmation du mot de passe'
                ],
                'invalid_message'=>'les mots de passe ne correspondent pas'
            ])
            ->add('sunmit', SubmitType::class,[
                'attr'=>'btn btn-primary'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
