<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Commentary;
use App\Form\CommentaryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentaryController extends AbstractController
{
    /**
     * @Route("/ajouter-un-commentaire?post_id{id}=", name="add_commentary" , methods={"GET|POST"})
     * @param Request $request
     * @param  EntityManagerInterface $entityManager
     * @return Response
     */
    public function addCommentary(Post $post, Request $request, EntityManagerInterface $entityManager) : Response
    {
      $commentary = new Commentary();

      $form = $this->createForm(CommentaryType::class,$commentary)->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){

      }

      return $this->render('renderer/form_commentary.html.twig',[
          'form' => $form->createView()    
        ]);
    }
}
