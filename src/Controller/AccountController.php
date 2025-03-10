<?php 

namespace App\Controller;

use App\Repository\AccountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\AccountType;
use App\Entity\Account;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountController extends AbstractController{

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher
    ){}

    public function showAllAccount(): Response{
        $accounts = $this->accountRepository->findAll();

        return $this->render('showall_users.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    public function addAccount(Request $request): Response{   
        
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);
        $msg = "";
        $status ="";

        if($form->isSubmitted()){
            try {

                $account->setPassword($this->hasher->hashPassword($account, $account->getPassword()));
                $account->setRoles("ROLE_USER"); 
                $this->em->persist($account);
                $this->em->flush();
                $msg = "Le compte a été ajouté avec succès";
                $status = "success";
            } catch (\Exception $e) {
                $msg =  $e->getMessage();
                $status = "danger";
            }
        }
        $this->addFlash($status, $msg);
        return $this->render('addaccount.html.twig',
        [
            'form'=> $form
        ]);
    }
}