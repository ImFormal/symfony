<?php 

namespace App\Controller;

use App\Entity\Account;
use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ApiAccountController extends AbstractController
{
    public function __construct(
        private AccountRepository $accountRepository
    ) {}

    public function getAllAccounts(): Response{

        return $this->json(
            $this->accountRepository->findAll(),
            200,
            [],
            ['groups' => 'account:read']
        );
    }
}