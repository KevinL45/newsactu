<?php

namespace App\Controller;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/inscription", name="user_register", methods={"GET|POST"})
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager):Response
    {
        # Instanciation d'un nouvelle utilisateur
        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(new DateTime());

        #Création d'un formulaire
        $form = $this->createFormBuilder($user)
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
        ])->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user->setPassword($userPasswordHasher->hashPassword($user,$user->getPassword()));

            
            $entityManager->persist($user);
            $entityManager->flush();

        $this->addFlash('success','Vous êtes bien inscrit');
        return $this->redirectToRoute('app_login');

        }

        return $this->render('user/register.html.twig',[
            'form'=>$form->createView()
        ]);


    }
}
