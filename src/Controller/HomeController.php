<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{

    public function hello(){
        return new Response('Hello World');
    }

    //#[Route(path:'helloworld', name:'app_home_helloworld')]
    public function helloWorld():Response{
        return new Response('Hello World');
    }

    #[Route(path:'/hello/{name}', name:'app_home_hello_test')]
    public function helloTo($name):Response{
        return new Response('Bonjour ' . $name);
    }

    public function index(): Response{
        return $this->render('index.html.twig');
    }
}