<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Model\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q',TextType::class, [
                'label'=> false,
                'required'=> false,
                'attr'=>[
                    'placeholder'=>' Rechercher',
                    'class'=>'form-control rounded-pill shadow-lg',
                ]
            ])
            ->add('categories',EntityType::class, [
                'label'=> false,
                'required'=> false,
                'class'=> Categorie::class,
                'multiple'=> true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class,[
                'label'=>"Filtrer",
                'attr'=>[
                    'class'=>'btn btn-dark my-3 rounded-pill border-1 shadow-lg'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class'=> SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix()
    {
        return '';
    }
}
