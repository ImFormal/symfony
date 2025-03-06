<?php 

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class ApiAccountController extends AbstractController
{
    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em,
        private readonly SerializerInterface $serializer
    ) {}

    public function getAllAccounts(): Response{

        return $this->json(
            $this->accountRepository->findAll(),
            200,
            [],
            ['groups' => 'account:read']
        );
    }

        public function addAccount(Request $request): Response{

        $data = $request->getContent();
        try {
            $account = $this->serializer->deserialize($data, Account::class, 'json');
        } catch (\Exception $e) {
            return $this->json(['error' => 'Erreur de désérialisation : ' . $e->getMessage()], 400);
        }

        $existingAccount = $this->accountRepository->findOneBy(['email' => $account->getEmail()]);
        
        if ($existingAccount) {
            return $this->json(
                ['error' => 'Un compte avec cet email existe déjà'],
                400
            );
        }

        try {
            $this->em->persist($account);
            $this->em->flush();
        } catch (\Exception $e) {
            return $this->json(
                ['error' => 'Erreur lors de la sauvegarde dans la base de données : ' . $e->getMessage()],
                500
            );
        }

        return $this->json(['message' => 'Compte créé avec succès'], 201);
    }
}