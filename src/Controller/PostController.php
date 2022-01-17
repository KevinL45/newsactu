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
     * @Route("/admin/creer-un-article", name="post_create_post", methods={"GET"})
     * @param Request $request
     * @param  EntityManagerInterface $entityManager
     * @return Response
     */
    public function createPost(Request $request, EntityManagerInterface $entityManager): Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post)
        ->handleRequest($request);

        return $this->render('post/form.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
