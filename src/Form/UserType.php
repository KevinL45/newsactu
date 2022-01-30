<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname',TextType::class,[
            'attr' => [
                'placeholder' => 'Prenom',
                'class' => 'form_control'
            ]
        ])
        ->add('lastname',TextType::class,[
            'attr' => [
                'placeholder' => 'Nom',
                'class' => 'form_control'
            ]
        ])
        ->add('email',TextType::class,[
            'attr' => [
                'placeholder' => 'exemple@hotmail.fr',
                'class' => 'form_control'
            ]
        ])
        ->add('password',PasswordType::class,[
            'attr' => [
                'placeholder' => 'Mot de passe',
                'class' => 'form_control'
            ]
        ])
        ->add('submit',SubmitType::class,[
            'label'=>'S\'inscrire',
            'attr' => [
                'class' => 'd-block col-2 mx-auto btn btn-warning'
            ]
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
