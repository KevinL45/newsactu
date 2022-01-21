<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    private SluggerInterface $sluggerInterface;

    public function __construct(SluggerInterface $sluggerInterface)
    {
        $this->sluggerInterface = $sluggerInterface;
    }
    /**
     * @Route("/admin/creer-une-categorie", name="create_category", methods={"GET|POST"})
     * @param Request $request
     * @param  EntityManagerInterface $entityManager
     * @return Response
     */
    public function createCategory(Request $request, EntityManagerInterface $entityManager)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class,$category)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category->setAlias($this->sluggerInterface->slug($category->getName()));
        

        $entityManager->persist($category);
        $entityManager->flush();
        $this->addFlash('success','Votre categorie est bien enrégistré');
        return $this->redirectToRoute('show_dashboard');
        }

        return $this->render('category/form.html.twig',[
            'form'=> $form->createView()
        ]);
    }
    /**
     * @Route("/admin/modifier-une-categorie/{id}", name="update_category", methods={"GET|POST"})
     * @param Category $category
     * @param Request $request
     * @param  EntityManagerInterface $entityManager
     * @return Response
     */
    public function updateCategory(Category $category,Request $request, EntityManagerInterface $entityManager):Response
    {
        $form = $this->createForm(CategoryType::class,$category)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category->setUpdatedAt(new DateTime());
            
            $category->setAlias($this->sluggerInterface->slug($category->getName()));

            $entityManager->persist($category);

            $entityManager->flush();

            $this->addFlash('success','Votre categorie est bien modifié');

            return $this->redirectToRoute('show_dashboard');

        }

        return $this->render('category/form.html.twig',[
            'form'=> $form->createView(),
            'category' => $category
        ]);
    }

    /**
     * @Route("/admin/supprimer-une-categorie/{id}", name="soft_delete_category", methods={"GET"})
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function softDeletedCategory(Category $category, EntityManagerInterface $entityManager) : Response
    {
        $category->setDeletedAt(new DateTime());
        $entityManager->persist($category);
        $entityManager->flush();

        $this->addFlash('success','Votre article est bien archivé');
        return $this->redirectToRoute('show_dashboard');

    }

     /**
     * @Route("/admin/enlever-une-categorie/{id}", name="hard_delete_category", methods={"GET"})
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function hardDeletedCategory(Category $category, EntityManagerInterface $entityManager) : Response
    {
        $category->setDeletedAt(new DateTime());
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success','Votre article est bien supprimé');
        return $this->redirectToRoute('show_dashboard');

    }

     /**
     * @Route("/admin/restaurer-une-categorie/{id}", name="restore_category", methods={"GET"})
     * @param Category $category
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function restoreCategory(Category $category, EntityManagerInterface $entityManager) : Response
    {
        $category->setDeletedAt(null);
        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash('success','Votre article est bien restaurée');
        return $this->redirectToRoute('show_dashboard');

    }

    
}
