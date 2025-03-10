<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;
use App\Form\CategoryType;
use App\Entity\Category;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

final class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $em
    ) {}

    public function showAllCategories(): Response
    {
        
        return $this->render('categories.html.twig', [
            'categories' => $this->categoryRepository->findAll(),
        ]);
    }

    public function addCategory(Request $request): Response
    {   
        
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        $msg = "";
        $status ="";
        if($form->isSubmitted()){
            try {
                $this->em->persist($category);
                $this->em->flush();
                $msg = "La catégorie a été ajoutée avec succès";
                $status = "success";
            } catch (\Exception $e) {
                $msg = $e->getMessage();
                $status = "danger";
            }
        }
        $this->addFlash($status, $msg);
        return $this->render('addcategory.html.twig',
        [
            'form'=> $form
        ]);
    }
}