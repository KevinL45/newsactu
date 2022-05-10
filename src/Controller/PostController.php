<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Commentary;
use App\Entity\Post;
use App\Form\PostType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\PostRemove;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class PostController extends AbstractController
{
    private SluggerInterface $sluggerInterface;

    public function __construct(SluggerInterface $sluggerInterface)
    {
        $this->sluggerInterface = $sluggerInterface;
    }

    /**
     * @Route("/admin/creer-un-article", name="create_post", methods={"GET|POST"})
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

            $post->setAuthor($this->getUser());

            $file = $form->get('photo')->getData();

            if($file){
                $extension = '.'.$file->guessExtension();
                $originalFilename = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
                $safeFilename = $this->sluggerInterface->slug($originalFilename);
                $newFilename = $safeFilename.'_'.uniqid().$extension;
            
            try {

                $file->move($this->getParameter('uploads_dir'),$newFilename);
                $post->setPhoto($newFilename);

            } catch (FileException $exception) {
                //code à exécuter

            }
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success','Votre article est bien enrégistré');
            return $this->redirectToRoute('default_home');


           }
        }

        return $this->render('post/form.html.twig',[
            'form' => $form->createView()
        ]);
    }
    /**
    * @Route("/voir/{cat_alias}/{post}_{id}", name="show_post", methods={"GET"})
    * @ParamConverter("post", options={"mapping": {"post" : "alias"} })
    * @param  Post $post
    * @return Response
    */
    public function showPost(Post $post, EntityManagerInterface $entityManager):Response
    {
        $commentaries = $entityManager->getRepository(Commentary::class)->findBy(['post' => $post->getId()]);

        return $this->render('post/show.html.twig',[
            'post' => $post,
            'commentaries' => $commentaries
        ]);
    }

    /**
     * @Route("/admin/modifier-un-article/{id}", name="update_post", methods={"GET|POST"})
     * @param Post $post
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     */
    public function updatePost(Post $post, EntityManagerInterface $entityManager, Request $request): Response
    {
        $originalPhoto = $post->getPhoto() ?? "pas de photo";

        $form = $this->createForm(PostType::class, $post, [
            'photo' => $originalPhoto
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setUpdatedAt(new DateTime());
            $post->setAlias($this->sluggerInterface->slug($post->getTitle()));

            /** @var UploadedFile $file */
            $file = $form->get('photo')->getData();

            if($file) {
                $extension = '.' . $file->guessExtension();
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $this->sluggerInterface->slug($originalFilename);
                # $safeFilename = $post->getAlias();
                $newFilename = $safeFilename . '_' . uniqid() . $extension;

                try {
                    # On a paramétré le chemin 'uploads_dir' dans le fichier config/services.yaml
                    $file->move($this->getParameter('uploads_dir'), $newFilename);

                    $post->setPhoto($newFilename);

                } catch (FileException $exception){
                    // code à exécuter si une erreur est attrapée.
                }
            } else {
                $post->setPhoto($originalPhoto);
            }# end if($file)

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', "L'article". $post->getTitle() ." à bien été modifié !");

            return $this->redirectToRoute('show_dashboard');

        }

        return $this->render('post/form.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);
    }
     /**
     * @Route("/admin/supprimer-un-article/{id}", name="delete_post", methods={"GET"})
     * @param Post $post
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function softDeletedPost(Post $post, EntityManagerInterface $entityManager) : Response
    {
        $post->setDeletedAt(new DateTime());
        $entityManager->persist($post);
        $entityManager->flush();

        $this->addFlash('success','Votre article est bien supprimé');
        return $this->redirectToRoute('show_dashboard');

    }
     /**
     * @Route("/voir/categorie/{alias}", name="show_posts_from_category", methods={"GET"})
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function showPostsFromCategry(Category $category, EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager->getRepository(Post::class)->findBy(['category'=>$category->getId()]);

        return $this->render('post/show_posts_from_category.html.twig',[
            'posts' => $posts,
            'category' => $category
        ]);
    }
}
