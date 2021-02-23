<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => ' Nom',
                'constraints' => new length([
                    'min'=>2,
                    'max'=>20,
                ]),
                'attr' => [
                    'placeholder' => 'votre nom'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => ' Prenom',
                'constraints' => new length([
                    'min'=>2,
                    'max'=>20,
                ]),
                'attr' => [
                    'placeholder' => 'votre prenom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'email',
                'constraints' => new length([
                   'min'=>2,
                    'max'=>50,
                ]),
                'attr' => [
                    'placeholder' => 'exemple@email.com'
                ]
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'les mots de passe doivent etre identique',
                'required' => true,
                'first_options' => [
                    'label' => 'mot de passe',
                    'attr' => [
                        'placeholder' => '************'
                    ],
                ],
                'second_options' => [
                    'label' => 'confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => '************'
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
