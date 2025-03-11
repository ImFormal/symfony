<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Account;
use App\Repository\AccountRepository;
use Exception;

class AccountService{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AccountRepository $accountRepository
    ){}

    public function save(Account $account){
        //Tester si les champs sont tous remplis
        if($account->getFirstname() != "" && $account->getLastname() != "" && $account->getEmail() != "" && $account->getPassword() != ""){
            //Tester si le compte n'existe pas en bdd
            if(!$this->accountRepository->findOneBy(["email"=>$account->getEmail()])){
                //Setter les paramètres
                $account->setRoles("ROLE_USER");
                $this->em->persist($account);
                $this->em->flush();
            }
            //Si le compte existe déjà
            else {
                throw new \Exception("Le compte existe déjà !", 400);
            }
        }
        //Si les champs ne sont pas remplis
        else {
            throw new \Exception("Les champs ne sont pas tous remplis !", 400);
        }
    }

    public function getAll(){
        $accounts = $this->accountRepository->findAll();

        if($accounts != ""){
            return $accounts;
        }
        else {
            throw new \Exception("La liste est vide !", 400);
        }
    }

    public function getById(int $id){
        $account = $this->accountRepository->findOneBy(["id" => $id]);

        if($account != ""){
            return $account;
        }
        else{
            throw new \Exception("L'utilisateur n'existe pas !", 400);
        }
    }
}