<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{

    /**
     * @Route("/admin/creer-un-article", name="post_create_post", methods={"GET|POST"})
     * @param Request $request
     * @param  EntityManagerInterface $entityManager
     * @return Response
     */
    public function createPost(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post)
        ->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            # La méthode getData()vous permet de récupérer les valeurs form et de les passer à l'objet $post
            # => En faite on "hydrate" notre objet Post des la ligne 26, donc pas nécceassaire de getData()ici
            //$post = $form->getData();
        }

        return $this->render('post/form.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
