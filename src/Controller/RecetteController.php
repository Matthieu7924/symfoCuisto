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
    /**
     * Undocumented function
     *
     * @param PaginatorInterface $paginator
     * @param RecetteRepository $recetteRepo
     * @param Request $request
     * @return Response
     */
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


    //CREATION RECETTE
    #[Route('/recette/creation', name: 'recette_new', methods:['GET', 'POST'])]
    /**
     * Undocumented function
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
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
            // dd($form->getData());
            $recette = $form->getData();
            $em->persist($recette);
            $em->flush();

            $this->addFlash(
                'success',
                'recette correctement ajoutée!'
            );
            
            return $this->redirectToRoute('app_recette');
        }
        return $this->render('pages/recette/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //EDITION RECETTE
    #[Route('/recette/edition/{id}', name: 'recette_update', methods:['GET', 'POST'])]
    /**
     * Undocumented function
     *
     * @param RecetteRepository $repo
     * @param integer $id
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function edit(
        RecetteRepository $repo,
        int $id,
        Request $request, 
        EntityManagerInterface $em 
        ):Response
    {
        //soit on passe par le repoitory avec comme paramètres de la fonction IngredientRepository $repo et  $id
        $recette = $repo->findOneBy(['id' => $id]);
        //soit on passe par l'objet
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $recette = $form->getData();
            $em->persist($recette);
            $em->flush($recette);
        
            $this->addFlash(
                'success',
                'Recette mise a jour effectuée'
            );
            return $this->redirectToRoute('app_recette');
        }
        return $this->render('pages/recette/edit.html.twig',[
            'form'=>$form->createView()
        ]);
    }


    //SUPPRESSION RECETTE
    #[Route('/recette/suppression/{id}', name: 'recette_delete', methods:['GET'])]
    /**
     * Undocumented function
     *
     * @param EntityManagerInterface $em
     * @param [type] $id
     * @return Response
     */
    public function delete(
        EntityManagerInterface $em, 
        $id): Response
    {
        $recette = $em->getRepository(Recette::class)->find($id);
        if(!$recette)
        {
            $this->addFlash(
                'success',
                'recette introuvable'
            );
            return $this->redirectToRoute('app_recette');
        }
            $em->remove($recette);
            $em->flush();
            $this->addFlash(
                'success',
                'recette correctement supprimée'
            );
            return $this->redirectToRoute('app_recette');
        
    }



}
