<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
    /**
     * This function displays all ingredients
     *
     * @param IngredientRepository $ingRepo
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/ingredient', name: 'app_ingredient', methods:['GET'])]
    public function index(IngredientRepository $ingRepo, Request $request, PaginatorInterface $paginator): Response
    {
        // $ingredients = $ingRepo->findAll();

        $ingredients = $paginator->paginate(
            $ingRepo->findAll(), // $query, query NOT result
            $request->query->getInt('page', 1), // page number
            10 // limit per page
        );

        $totalIngredients = $ingredients->getTotalItemCount();


        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
            'totalIng' => $totalIngredients
        ]);
    }
}
