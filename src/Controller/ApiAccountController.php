<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Account;
use App\Repository\AccountRepository;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class ApiAccountController extends AbstractController
{

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly ArticleRepository $articleRepository
    
    ) {}

    public function getAllAccounts(): Response{

        return $this->json(
            $this->accountRepository->findAll(),
            200,
            ["Access-Control-Allow-Origin" => $this->getParameter('allowed_origin')],
            ['groups' => 'account:read']
        );
    }

    public function addAccount(Request $request, UserPasswordHasherInterface $hasher): Response
    {
        $json = $request->getContent();
        $account = $this->serializer->deserialize($json, Account::class, 'json');
        //test si les champs sont remplis
        if ($account->getFirstname() && $account->getLastname() && $account->getEmail() && $account->getPassword() && $account->getRoles()) {
            //Test si le compte n'existe pas
            if (!$this->accountRepository->findOneBy(["email" => $account->getEmail()])) {
                $account->setPassword($hasher->hashPassword($account, $account->getPassword()));
                $this->em->persist($account);
                $this->em->flush();
                $code = 201;
            }
            //Sinon il existe déja
            else {
                $account = "Account existe déja";
                $code = 400;
            }
        }
        //Sinon les champs ne spont pas remplis
        else {
            $account = "Veuillez remplir tous les champs";
            $code = 400;
        }
        //Retourner la réponse json
        return $this->json($account, $code, [
            "Access-Control-Allow-Origin" => "*",
        ], ["groups" => "account:create"]);
    }

    public function updateAccount(Request $request, string $email): Response{

        $json = $request->getContent();
        $account = $this->serializer->deserialize($json, Account::class, 'json');

        if ($account->getFirstname() && $account->getLastname()) {
            $existingAccount = $this->accountRepository->findOneBy(['email' => $email]);
            
            if ($existingAccount) {
                $existingAccount->setFirstname($account->getFirstname());
                $existingAccount->setLastname($account->getLastname());
                $this->em->persist($existingAccount);
                $this->em->flush();
                $code = 201;
            }
            else {
                $account = "Account n'existe pas";
                $code = 404;
            }
        }
        else {
            $account = "Veuillez remplir tous les champs";
            $code = 400;
        }


        return $this->json($account, $code, 
            ["Access-Control-Allow-Origin" => "*",], ["groups" => "account:update"]);
    }


    public function deleteAccount(Request $request, int $id): Response{

        $existingAccount = $this->accountRepository->find($id);

        if($existingAccount) {

            $articles = $this->articleRepository->findBy(['author' => $existingAccount]);
            foreach ($articles as $article) {
                $this->em->remove($article);
            }

            $this->em->remove($existingAccount);
            $this->em->flush();
            $account = "Account supprimé avec succès !";
            $code = 201;
        }
        else {
            $account = "Account n'existe pas";
            $code = 404;
        }

        return $this->json($account, $code, 
            ["Access-Control-Allow-Origin" => "*",], ["groups" => "account:delete"]);
    }

    public function updatePassword(Request $request, int $id): Response{

        $json = $request->getContent();
        $account = $this->serializer->deserialize($json, Account::class, 'json');

        $existingAccount = $this->accountRepository->find($id);

        if($account->getPassword()){
            if ($existingAccount) {
                $hashedPassword = $this->passwordHasher->hashPassword($existingAccount, $account->getPassword());
                $existingAccount->setPassword($hashedPassword);
                $this->em->persist($existingAccount);
                $this->em->flush();
                $code = 201;
                $account = "Mot de passe changé !";
            }
            else {
                $account = "Account n'existe pas";
                $code = 404;
            }
        }

        else{
            $account = "Veuillez remplir tous les champs";
            $code = 400;
        }

        return $this->json($account, $code, 
            ["Access-Control-Allow-Origin" => "*",], ["groups" => "password:update"]);
    }
}