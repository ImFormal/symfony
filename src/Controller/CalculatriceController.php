<?php

namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculatriceController extends AbstractController{

    #[Route(path:'/calculatrice/{n1}/{operator}/{n2}', name:'app_home_calculatrice')]
    public function calculatrice(int $n1, string $operator, int $n2): Response {
        try {
            $resultat = null;

            switch($operator) {
                case 'add':
                    $resultat = $n1 + $n2;
                    break;
                case 'sous':
                    $resultat = $n1 - $n2;
                    break;
                case 'multi':
                    $resultat = $n1 * $n2;
                    break;
                case 'div':
                    if ($n2 == 0) {
                        throw new Exception("divide by 0");
                    }
                    $resultat = $n1 / $n2;
                    break;
                default:
                    throw new Exception("operator");
            }

            return $this->render('calculatrice.html.twig', [
                'nbr1' => $n1,
                'nbr2' => $n2,
                'operateur' => $operator,
                'resultat' => $resultat
            ]);

        } catch (Exception $e) {
            $errorMessage = '';
            switch($e->getMessage()) {
                case 'divide by 0':
                    $errorMessage = 'Il est impossible de diviser par 0.';
                    break;
                case 'operator':
                    $errorMessage = 'L\'opérateur n\'est pas valide !';
                    break;
                default:
                    $errorMessage = 'Une erreur inconnue est survenue.';
                    break;
            }

            return $this->render('calculatrice/error.html.twig', [
                'message' => $errorMessage
            ]);
        }
    }
}
