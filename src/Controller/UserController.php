<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/utilisateur/edition/{id}', name: 'user.edit')]
    public function edit(
        int $id, 
        Request $request, 
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
        ): Response {
        $user = $this->entityManager->getRepository(User::class)->find($id);
    
        if ($user === null) {
            $this->addFlash(
                'error',
                'Cet utilisateur n\'existe pas.'
            );
            return $this->redirectToRoute('app_recette');
        }
    
        if (!$this->getUser()) {
            return $this->redirectToRoute('security.login');
        }
    
        $form = $this->createForm(UserType::class, $user);
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($user, $form->get('plainPassword')->getData())) {
                // Le mot de passe est correct, vous pouvez enregistrer les modifications
                $updatedUser = $form->getData();
                $manager->persist($updatedUser);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    'Les informations de votre compte ont bien été modifiées.'
                );
    
                return $this->redirectToRoute('app_recette');
            } 
            else{
                $this->addFlash(
                    'warning',
                    'Le mot de passe saisi est incorrect.'
                );
            }
        }
    
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // #[Route('/utilisateur/edition-mdp/{id}', name:'user.edit.password', methods:['GET', 'POST'])]
    // public function editPassword(
    //     User $user,
    //     Request $request
    //     ) : Response
    // {
    //     $form = $this->createForm(UserPasswordType::class);
    //     return $this->render('pages/user/edit_password.html.twig',[
    //         'form'=>$form->createView()
    //     ]);
    // }

    #[Route('/utilisateur/edition-mdp/{id}', name: 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(
        int $id, 
        EntityManagerInterface $manager, 
        Request $request,
        UserPasswordHasherInterface $hasher): Response
    {
        $user = $manager->getRepository(User::class)->find($id);

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            if($hasher->isPasswordValid($user, $form->getData()['plainPassword']))
            {
                    $user->setPassword(
                        $hasher->hashPassword(
                            $user,
                            $form->getData()['newPassword']
                        )
                    );

                // dd($user);

                $this->addFlash(
                    'success',
                    'Le mot de passe a été correctement modifié.'
                );

                $manager->persist($user);
                $manager->flush();
                
                return $this->redirectToRoute('app_recette');
            }
            else
            {
                $this->addFlash(
                    'warning',
                    'Le mot de passe saisi est incorrect.'
                );
            }
        }
        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }


}