<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientRepository $ingRepo): Response
    {
        $ingredients = $ingRepo->findAll();
        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' =>$ingredients
        ]);
    }
}
