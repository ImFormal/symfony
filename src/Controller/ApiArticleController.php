<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\AccountRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;

final class ApiArticleController extends AbstractController{
    public function __construct(
        private readonly ArticleRepository $articleRepository,
        private readonly AccountRepository $accountRepository,
        private readonly CategoryRepository $categoryRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    ) {}

    public function getAllArticles(): Response{

        return $this->json(
            $this->articleRepository->findAll(),
            200,
            [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ],
            ['groups' => 'article:read']
        );
    }

    public function getArticleById(int $id): Response {

        $article = $this->articleRepository->find($id);
        $code = 200;

        if(!$article){
            $article = "Article n'existe pas";
            $code = 404;
        }

        return $this->json(
            $article,
            $code,
            [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ],
            ['groups' => 'article:read']
        );
    }

    public function addArticle(Request $request): Response
    {
        $json = $request->getContent();
        $article = $this->serializer->deserialize($json, Article::class, 'json');
        //test si les champs sont remplis
        if ($article->getTitle() && $article->getContent() && $article->getAuthor()) {
            //récupération du compte
            $article->setAuthor($this->accountRepository->findOneBy(["email" => $article->getAuthor()->getEmail()]));
            //Récupération des catégories
            foreach ($article->getCategories() as $key => $value) {
                $cat = $value->getName();
                $article->removeCategory($value);
                $cat = $this->categoryRepository->findOneBy(["name" => $cat]);
                $article->addCategory($cat);
            }
            //Test l'article n'existe pas
            if (!$this->articleRepository->findOneBy(["title" => $article->getTitle(), "content" => $article->getContent()])) {
                $this->entityManager->persist($article);
                $this->entityManager->flush();
                $code = 201;
            } else {
                $code = 400;
                $article = "Article existe déjà";
            }
        } else {
            $code = 400;
            $article = "Champs non remplis";
        }
        return $this->json(
            $article,
            $code,
            [
                "Access-Control-Allow-Origin" => "*",
                "Content-Type" => "application/json"
            ],
            ['groups' => 'articles:read']
        );
    }
}
