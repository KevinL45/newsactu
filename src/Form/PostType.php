<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'Titre de l\'article',
                'required'=> true,
                'attr' => [
                    'form' => 'form-control'
                ]
            ])
            //->add('alias')
            ->add('content',TextareaType::class,[
                'label' => false,
                'required'=> true,
                'attr' => [
                    'placeholder' => 'Ici le contenu de l\'article',
                    'form' => 'form-control'
                ]
            ])
            ->add('photo', FileType::class,[
                'label' => 'Photo de l\'article',
                'data_class' => null,
                'attr' => [
                    'form' => 'form-control'
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg','image/png'],
                        'mimeTypesMessage' => 'Les types de photo autorisés sont : jpeg ou png'
                    ])
                ]
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Publier',
                'attr'=>[
                    'class'=>'d-block col-2 mx-auto btn btn-warning'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            # Cette paire permet de représenter l'entité Post pour le FormBuilder.
            'data_class' => Post::class,
            'allow_file_upload' => true,
        ]);
    }
}
