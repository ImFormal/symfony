<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController{

    public function register(): Response{
        return $this->render('register.html.twig');
    }

    public function login(): Response{
        return $this->render('login.html.twig');
    }
}