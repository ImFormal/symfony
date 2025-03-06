<?php 

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiArticleController extends AbstractController{
    public function __construct(
        private ArticleRepository $articleRepository
    ) {}

    public function getAllArticles(): Response{

        return $this->json(
            $this->articleRepository->findAll(),
            200,
            [],
            ['groups' => 'article:read']
        );
    }

    public function getArticleById(int $id): Response {
        return $this->json(
            $this->articleRepository->find($id),
            200,
            [],
            ['groups' => 'article:read']
        );
    }
}