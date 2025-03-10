<?php

namespace App\Controller;

use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleType;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

class ArticleController extends AbstractController
{
    

    public function __construct(
        private readonly ArticleRepository $articleRepository, 
        private readonly EntityManagerInterface $em,
    ){}

    public function showAllArticle(): Response{
        $articles = $this->articleRepository->findAll();

        return $this->render('showall_articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function articleId(int $id): Response {
        $article = $this->articleRepository->find($id);

        if (!$article) {
            return $this->redirectToRoute('article_show_all');
        }

        return $this->render('article_detail.html.twig', [
            'article' => $article,
        ]);
    }

    public function addArticle(Request $request): Response{   
        
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $msg = "";
        $status ="";

        if($form->isSubmitted()){
            try {

                $article->setCreateAt(new DateTime());
                $this->em->persist($article);
                $this->em->flush();
                $msg = "L'article a été ajouté avec succès";
                $status = "success";
            } catch (\Exception $e) {
                $msg =  $e->getMessage();
                $status = "danger";
            }
        }
        $this->addFlash($status, $msg);
        return $this->render('addarticle.html.twig',
        [
            'form'=> $form
        ]);
    }

}