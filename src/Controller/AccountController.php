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
use App\Service\AccountService;

class AccountController extends AbstractController{

    public function __construct(
        private readonly AccountRepository $accountRepository,
        private readonly AccountService $accountService,
        private readonly EntityManagerInterface $em,
        private readonly UserPasswordHasherInterface $hasher
    ){}

    public function register(): Response{
        return $this->render('register.html.twig');
    }

    public function login(): Response{
        return $this->render('login.html.twig');
    }

    public function showAllAccount(): Response{

        try{
            $accounts = $this->accountService->getAll();
        } catch(\Exception $e){
            $e->getMessage();
        }
        
        return $this->render('showall_users.html.twig', [
            "accounts" => $accounts
        ]);
    }

    public function showById(int $id): Response{

        try{
            $account = $this->accountService->getById($id);
        } catch(\Exception $e){
            $e->getMessage();
        }

        return $this->render('show_user.html.twig', [ 
            "account" => $account??null
        ]);
    }

    public function addAccount(Request $request): Response
    {

        $user = new Account();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        $type = "";
        $msg = "";
        //test si le formulaire est submit
        if($form->isSubmitted() && $form->isValid()) {
            try {
                //Appel de la méthode save d'AccountService
                $this->accountService->save($user);
                $type = "success";
                $msg = "Le compte a été ajouté en BDD";
            } 
            //Capturer les exceptions (erreurs)
            catch (\Exception $e) {
                $type = "danger";
                $msg = $e->getMessage();
            }
            
            $this->addFlash($type, $msg);
        }
        return $this->render('addaccount.html.twig',[
            'formulaire' =>$form
        ]);
    }
}

