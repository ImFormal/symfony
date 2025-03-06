<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\ArticleRepository;

class ArticleController extends AbstractController
{
    private ArticleRepository $articleRepository;

    public function __construct(ArticleRepository $articleRepository){
        $this->articleRepository = $articleRepository;
    }

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
}