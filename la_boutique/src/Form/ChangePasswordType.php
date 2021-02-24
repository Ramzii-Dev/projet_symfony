<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'disabled'=>true,
                'label' => ' Mon adress email',
                'attr' => [
                    'placeholder' => 'votre email',
                ]
            ])
            ->add('firstname',TextType::class,[
                'disabled'=>true,
                'label' => ' Mon prenom',
            ])
            ->add('lastname',TextType::class,[
                'disabled'=>true,
                'label' => ' Mon nom',
            ])
            ->add('old_password',PasswordType::class,[
                'label' => ' mot de passe actuel',
                'mapped'=>false,
                'attr' => [
                    'placeholder' => '*****mot de passe actuel******'
                ],
            ])
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'les mots de passe doivent etre identique',
                'required' => true,
                'mapped'=>false,
                'first_options' => [
                    'label' => 'votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => '******votre nouveau mot de passe******'
                    ],
                ],
                'second_options' => [
                    'label' => 'confirmez votre nouveau mot de passe ',
                    'attr' => [
                        'placeholder' => '******confirmez votre nouveau mot de passe******'
                    ],
                ],
            ])
        ->add('envoyer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
