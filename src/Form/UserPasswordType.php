<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;

use Assert\NotBlank;


class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options):void
    {
        $builder
            ->add('plainPassword', RepeatedType::class,[
                'type' => PasswordType::class,
                'first_options' =>[
                    'attr' =>[
                        'class'=>'form-control'
                    ],
                    'label' => 'Mot de passe',
                    'label_attr' =>[
                        'class' => 'mt-4 form-label'
                    ]
                ],
                'second_options'=>[
                    'attr' =>[
                        'class'=>'form-control'
                    ],
                    'label' => 'Confirmation de mot de passe',
                    'label_attr' =>[
                        'class' => 'mt-4 form-label'
                    ]
                ],
                'invalid_message'=>'les mots de passe ne correspondent pas'
            ])
            ->add('newPassword', PasswordType::class,[
                'attr'=>[
                    'class'=>'form-control'
                ],
                'label' => 'Nouveau mot de passe',
                'label_attr' =>[
                    'class' => 'mt-4 form-label'
                ],
                'constraints'=> [new Assert\NotBlank()]
            ])
            ->add('submit', SubmitType::class,[
                'attr'=>[
                    'class'=>'mt-4 btn btn-primary'
                ],
                'label' => 'Changer mon mot de passe'
                ]);
    }

    
}

