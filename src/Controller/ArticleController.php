<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    public function allArticles(): Response{
        $articles = [
            ['id' => 1, 'title' => 'Article 1', 'content' => 'Contenu de l\'article 1', 'date' => '2025-03-01'],
            ['id' => 2, 'title' => 'Article 2', 'content' => 'Contenu de l\'article 2', 'date' => '2025-03-02'],
            ['id' => 3, 'title' => 'Article 3', 'content' => 'Contenu de l\'article 3', 'date' => '2025-03-03'],
        ];

        return $this->render('article.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function articleId(int $id): Response{
        $article = [
            'id' => $id,
            'title' => 'Article ' . $id,
            'content' => 'Détail complet de l\'article ' . $id . '. Voici l\'article dans son intégralité.',
            'date' => '2025-03-' . $id,
        ];

        return $this->render('article_detail.html.twig', [
            'article' => $article,
        ]);
    }
}
