<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class DashboardController extends AbstractController
{


    /**
     * @Route("/admin/tableau-de-bord", name="show_dashboard", methods={"GET"})
    * @param  EntityManagerInterface $entityManager
    * @IsGranted("ROLE_ADMIN")
     * @return Response
     */
    public function showDashboard(EntityManagerInterface $entityManager)
    {
        //$this->denyAccessUnlessGranted("ROLE_ADMIN");
        
        
        $posts = $entityManager->getRepository(Post::class)->findBy(['deletedAt' => null]);
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findBy(['deletedAt' => null]);

        return $this->render('dashboard/show_dashboard.html.twig',[
            'posts' => $posts,
            'categories' => $categories,
            'users' => $users

        ]);

    }
}
