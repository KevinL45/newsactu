<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
    private SluggerInterface $sluggerInterface;

    public function __construct(SluggerInterface $sluggerInterface)
    {
        $this->sluggerInterface = $sluggerInterface;
    }

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
            $post->setAlias($this->sluggerInterface->slug($form->get('title')->getData()));
            # La méthode getData() vous permet de récupérer les valeurs form et de les passer à l'objet $post
            # => En faite on "hydrate" notre objet Post des la ligne 26, donc pas nécceassaire de getData()ici
            //$post = $form->getData();

            $file = $form->get('photo')->getData();

            if($file){
                $extension = '.'.$file->guessExtension();
                $originalFilename = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $this->sluggerInterface->slug($originalFilename);
                $newFilename = $safeFilename.'_'.uniqid().$extension;
            
            try {

                $file->move($this->getParameter('uploads_dir'),$newFilename);

            } catch (FileException $exception) {
                //code à exécuter

            }

           }
        }

        return $this->render('post/form.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
