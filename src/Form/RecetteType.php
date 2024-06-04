<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Difficulte;
use App\Entity\Ingredients;
use App\Entity\Provinces;
use App\Entity\Recette;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichFileType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',TextType::class,[
                'label' => 'Nom de la recette',
                'attr'=>[
                    'placeholder'=>' Merci de saisir votre prénom',
                    'class'=>'form-control rounded-pill'
                ]
            ])

            ->add('tempsPreparation', TimeType::class,[
                    'label'=>"Temps de préparation (H : M )",
                    'attr'=>[
                        'class'=>'form-control rounded-pill']]
           )
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'required' =>true,
                'attr'=>[
                    'class'=>'form-control rounded-pill'
                ]
            ])
            ->add('difficulte',EntityType::class, [
                'class' => Difficulte::class,
                'label'=> 'Niveau de difficulté',
                'required' =>true,
                'attr'=>[
                    'class'=>'form-control rounded-pill'
                ]
            ])
            ->add('ingredients', EntityType::class, [
                'class' => Ingredients::class,
                'label' => 'choisissez vos ingrédients(un à plusieurs ingrédients au choix)',
                'attr'=>[
                    'class'=>'select2 form-control rounded-pill'
                ],
                'multiple'=> true,
                'required' =>true
            ])
            ->add('province', EntityType::class, [
                'class' => Provinces::class,
                'attr'=>[
                    'class'=>'select2  form-control rounded-pill'
                ],
                'required'=>false,
            ])

            ->add('imageFile', FileType::class, [
                'label' => 'ajouter une photo pour votre recette(JPG ou PNG) ',
                'attr'=>[
                    'class'=>'form-control rounded-pill'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '10000K',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Merci de télécharger un fichier valide',
                    ])
                ]
            ])
            ->add('description',TextareaType::class,[
                'attr'=> [
                    'class'=> "form-control rounded-4",
                    "rows"=> "8",
                ]
            ])
            ->add('isPublic', CheckboxType::class,[
                'label' => 'Recette publique',
                'required'=>false,
            ])
            ->add('submit', SubmitType::class,[
                'label'=>"Valider",
                'attr'=>[
                    'class'=>'btn btn-dark my-3 form-control rounded-pill'
                ]
            ])

        ;
        $builder->addEventListener(FormEvents::SUBMIT, function (SubmitEvent $event) {
            /** @var Recette $recette */
            $recette = $event->getData();
            if (null === $recette->getSlug() && $recette !== $recette->getLibelle()) {
                $slugger = new AsciiSlugger();
                $recette->setSlug($slugger->slug($recette->getLibelle())->lower());
            }
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
