<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Account;
use App\Entity\Article;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');
        $accounts = [];
        $categories = [];
        $articles = [];

        for ($i=0; $i < 50 ; $i++) { 
            //Ajouter un compte
            $account = new Account();
            $account->setFirstname($faker->firstName())
                    ->setLastname($faker->lastName())
                    ->setEmail($faker->email())
                    ->setPassword($faker->password())
                    ->setRoles("ROLE_USER");

            $accounts[$i] = $account; 
            //Ajout en cache
            $manager->persist($account);
        }

        for ($i=0; $i < 30 ; $i++) { 
            //Ajouter une catégorie
            $category = new Category();
            $category->setName($faker->word());

            $categories[$i] = $category;
            //Ajout en cache
            $manager->persist($category);
        }
        
        //Enregistrement en base de données     
        $manager->flush();

        for ($i=0; $i < 100 ; $i++) { 
            $authorId = rand(0, 49);
            $author = $accounts[$authorId];

            //Ajouter un article
            $article = new Article();
            $article->setTitle($faker->title())
                    ->setContent($faker->paragraph())
                    ->setCreateAt($faker->dateTime())
                    ->setAuthor($author);

            $articles[$i] = $article;

            $assignedCategories = [];
            while(count($assignedCategories) < 3){
                $categoryId = rand(0, 29);
                if (!in_array($categoryId, $assignedCategories)) {
                    $assignedCategories[] = $categoryId;
                    $category = $categories[$categoryId];
                    $article->addCategory($category);
                }
            }

            //Ajout en cache
            $manager->persist($article);
        }
        
        //Enregistrement en base de données     
        $manager->flush();
    }
}
