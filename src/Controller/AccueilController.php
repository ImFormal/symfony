<?php

namespace App\Controller;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController{

    #[Route(path:'/addition/{n1}/{n2}', name:'app_home_addition')]
    public function addition(int $n1, int $n2){
        
        if($n1 < 0 || $n2 < 0){
            return new Response("<p>Les nombres sont négatifs</p>");
        }

        return new Response("<p>L'addition de " . $n1 . " et " . $n2 . " est égale au résultat : " . $n1+$n2 . "</p>");
    }

    #[Route(path:'/calculatrice/{n1}/{operator}/{n2}', name:'app_home_calculatrice')]
    public function calculatrice(mixed $n1, string $operator, mixed $n2){

        try{

            if(!is_numeric($n1) || !is_numeric($n2)){
                throw new Exception("not num");
            }
    
            switch($operator){
                case "add":
                    return new Response("<p>L'addition de " . $n1 . " et " . $n2 . " est égale au résultat : " . $n1+$n2 . "</p>");
                case "sous":
                    return new Response("<p>La soustraction de " . $n1 . " et " . $n2 . " est égale au résultat : " . $n1-$n2 . "</p>");
                case "multi":
                    return new Response("<p>La multiplication de " . $n1 . " et " . $n2 . " est égale au résultat : " . $n1*$n2 . "</p>");
                case "div":
                    if($n2 == 0){
                        throw new Exception("divide by 0");
                    }
                    return new Response("<p>La division de " . $n1 . " et " . $n2 . " est égale au résultat : " . $n1/$n2 . "</p>");
                default:
                    throw new Exception("operator");
            }
        } catch(Exception $e){
            switch($e->getMessage()){
                case "not num":
                    return new Response("<p>Les informations rentrées ne sont pas des nombres.</p>");
                case "divide by 0":
                    return new Response("<p>Il est impossible de divisé par 0.</p>");
                case "operator":
                    return new Response("<p>L'opérateur n'est pas valide !</p>");
                default:
                    return new Response("<p>Erreur</p>");
            }
        }
        
    }    
}