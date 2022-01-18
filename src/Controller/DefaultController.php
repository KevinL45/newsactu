<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * ex : https://127.0.0.1:8000
     * @Route("/", name="default_home", methods={"GET"})
     */
    public function home()
    {
        $posts = $this->entityManager->getRepository(Post::class);

        return $this->render('default/home.html.twig',[
            'posts' => $posts
        ]);
    }
}
