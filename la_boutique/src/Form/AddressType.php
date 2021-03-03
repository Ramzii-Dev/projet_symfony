<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class,[
                'label'=>'votre adresse complete ',
                'attr'=>[
                    'placeholder'=>'Exp : 6 rue marcel sembat ....'
                ]
            ])
            ->add('firstname',TextType::class,[
                'label'=>'Nom',
                'attr'=>[
                    'placeholder'=>''
                ]
            ])
            ->add('lastname',TextType::class,[
                'label'=>'Prénom',
                'attr'=>[
                    'placeholder'=>''
                ]
            ])
            ->add('company',TextType::class,[
                'label'=>'Entreprise',
                'required'=>'0',
                'attr'=>[
                    'placeholder'=>'(facultatif)'
                ]
            ])
            ->add('postal',TextType::class,[
                'label'=>'Code Postal',
                'attr'=>[
                    'placeholder'=>''
                ]
            ])
            ->add('city',TextType::class,[
                'label'=>'Ville',
                'attr'=>[
                    'placeholder'=>''
                ]
            ])
            ->add('country',CountryType::class,[
                'label'=>'Pays',
                'attr'=>[
                    'placeholder'=>''
                ]
            ])
            ->add('mobile',TelType::class,[
                'label'=>'Telephone Mobile',
                'attr'=>[
                    'placeholder'=>''
                ]
            ])
            ->add('phone',TelType::class,[
                'label'=>'Telephone Fixe',
                'required'=>'0',
                'attr'=>[
                    'placeholder'=>'(facultatif)'
                ]
            ])
            ->add('submit',SubmitType::class,[
                'label'=>'ajouter',
                'attr'=>[
                    'class'=>'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
