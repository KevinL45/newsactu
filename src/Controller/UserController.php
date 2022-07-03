<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Entity\Favoris;
use App\Entity\Post;
use App\Entity\User;
use App\Form\UserType;
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
    public function register(Request $request, 
    UserPasswordHasherInterface $userPasswordHasher, 
    EntityManagerInterface $entityManager):Response
    {
        # Instanciation d'un nouvelle utilisateur
        $user = new User();
        $user->setRoles(["ROLE_USER"]);
        $user->setCreatedAt(new DateTime());
        $user->setUpdatedAt(new DateTime());

        #Création d'un formulaire
        $form = $this->createForm(UserType::class,$user)->handleRequest($request);

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

    /**
     * @Route("/user/mon-espace-perso", name="show_profile", methods={"GET"})
     */
    public function showProfile(EntityManagerInterface $entityManager):Response
    {
        $favoris = $entityManager->getRepository(Favoris::class)->findBy(['user'=>$this->getUser()]);
        $commentaries = $entityManager->getRepository(Commentary::class)->findBy(['author'=>$this->getUser()]);

        return $this->render('user/show.html.twig',[
            'commentaries' => $commentaries,
            'favoris' => $favoris
        ]);

    }
}
