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

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'Titre de vos article',
                'required'=> true,
                'attr'=>[
                    'form'=>'form_control'
                ]
                
            ])
            //->add('alias')
            ->add('content',TextareaType::class,[
                'label' => false,
                'required'=> true,
                'attr' => [
                    'placeholder' => 'Ici le contenu de l\'article'
                ]
            ])
            ->add('photo', FileType::class,[
                'label' => 'Photo de l\'article',
                'data_class' => null
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
