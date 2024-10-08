<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom',TextType::class, [
                'label'=>'Votre prénom',
                'attr'=>[
                    'placeholder'=>' Merci de saisir votre prénom',
                    'class'=>'form-control rounded-pill'
                ]
            ])
            ->add('email', EmailType::class,[
                'label'=>'Votre Email',
                'attr'=>[
                    'placeholder'=>' Merci de saisir votre email',
                    'class'=>'form-control rounded-pill'
                ]
            ])
            ->add('Message', TextareaType::class,[
                'label'=>'Votre message',
                'attr'=>[
                    'placeholder'=>' Merci de saisir votre message',
                    'class'=>'form-control rounded-4',
                    "rows"=> "6",
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
