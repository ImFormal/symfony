<?php 

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class AccountController extends AbstractController{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository){
        $this->accountRepository = $accountRepository;
    }

    public function showAllAccount(): Response{
        $accounts = $this->accountRepository->findAll();

        return $this->render('showall_users.html.twig', [
            'accounts' => $accounts,
        ]);
    }
}