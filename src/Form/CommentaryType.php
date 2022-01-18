<?php

namespace App\Form;

use App\Entity\Commentary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comments',TextareaType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ecrivez votre commentaire',
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Publier',
                'attr' => [
                    'class' => 'd-block col-2 mx-auto btn btn-warning'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentary::class,
        ]);
    }
}
