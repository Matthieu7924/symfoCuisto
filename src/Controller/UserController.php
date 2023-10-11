<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/utilisateur/edition/{id}', name: 'user.edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($id);

        if(!$this->getUser())
        {
            return $this->redirectToRoute('security.login');
        }

        // dd($user, $this->getUser());

        if($this->getUser() !== $user)
        {
            return $this->redirectToRoute('app_recette');
        }

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid())
        {
            $user = $form->getData();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'les infos de votre compte ont bien été modifiées'
            );
            return $this->redirectToRoute('app_recette');
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
