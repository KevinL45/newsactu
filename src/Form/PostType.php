<?php
namespace App\Form;

use App\Entity\Category;
use App\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de votre article',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Le nombre de caractères minimal est {{ limit }} (votre titre {{ value }})',
                        'maxMessage' => 'Le nombre de caractères maximal est {{ limit }} (votre titre {{ value }})'
                    ])
                ],
            ])
            ->add('subtitle', TextType::class, [
                'label' => 'Sous-titre de l\'article',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut être vide'
                    ]),
                    new Length([
                        'min' => 3,
                        'max' => 255,
                        'minMessage' => 'Le nombre de caractères minimal est {{ limit }} (votre titre {{ value }})',
                        'maxMessage' => 'Le nombre de caractères maximal est {{ limit }} (votre titre {{ value }})'
                    ])
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Ici le contenu de l\'article',
                    'class' => 'form-control',
                ],
//                'constraints' => [
//                    new NotBlank([
//                        'message' => 'Ce champ ne peut être vide'
//                    ])
//                ]
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Choisissez une catégorie',
                'attr' => [
                    'class' => 'form-control'
                ],
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('c')
                ->where('c.deletedAt is null');
                }
                
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de l\'article',
                'data_class' => null,
                'attr' => [
                    'class' => 'form-control',
                    'data-default-file' => $options['photo']
                    ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les types de photo autorisées sont : .jpeg et .png'
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            # Cette paire permet de représenter l'entité Post pour le FormBuilder.
            'data_class' => Post::class,
            'allow_file_upload' => true,
            'photo' => null
        ]);
    }
}
?>
