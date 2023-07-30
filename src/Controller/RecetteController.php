<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'app_recette', methods:['GET'])]
    public function index(
        PaginatorInterface $paginator,
        RecetteRepository $recetteRepo,
        Request $request
    ): Response
    {

        $recettes = $paginator->paginate(
            $recetteRepo->findAll(), // $query, query NOT result
            $request->query->getInt('page', 1), // page number
            10 // limit per page
        );

        return $this->render('pages/recette/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recette/creation', name: 'recette_new', methods:['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $recette = new Recette();
        $form = $this->createForm(RecetteType::class, $recette);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) // Check if the form is submitted and valid
        {
            $recette = $form->getData();
            $em->persist($recette);
            $em->flush();

            $this->addFlash(
                'success',
                'recette correctement ajoutée!'
            );
            
            return $this->redirectToRoute('pages/recette/index.html.twig');
        }
        return $this->render('pages/recette/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
