<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/ingredient/nouveau', name: 'ingredient_new', methods:['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em
        ):Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            $ingredient =$form->getData();

            $em->persist($ingredient);
            $em->flush($ingredient);

            return $this->redirectToRoute('app_ingredient');

        }


        return $this->render('pages/ingredient/new.html.twig',[
            'form' => $form->createView()
        ]);
    }
}
