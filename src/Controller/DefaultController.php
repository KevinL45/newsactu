<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * ex : https://127.0.0.1:8000
     * @Route("/", name="default_home", methods={"GET"})
     */
    public function home(){
        return $this->render('default/home.html.twig');
    }
}
