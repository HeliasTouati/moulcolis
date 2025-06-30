<?php

namespace App\Controller;

use App\Entity\Addresses;
use App\Form\AddressesForm;
use App\Form\ChangePasswordForm;
use App\Form\UsersForm;
use App\Repository\AddressesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, EntityManagerInterface $entityManager, AddressesRepository $addressesRepository): Response
    {

        return $this->render('profil/index.html.twig', [
            'address' => $addressesRepository->findAll(),
        ]);
    }

    #[Route('/profil/addresses', name: 'app_profil_addresses')]
    public function addresses(Request $request, EntityManagerInterface $entityManager): Response
    {
      $adresse = new Addresses();
      $currentUser = $this->getUser();
      $form = $this->createForm(AddressesForm::class, $adresse, [
          'current_user' => $currentUser
      ]);
      $form->handleRequest($request);


      if ($form->isSubmitted() && $form->isValid())
      {
          $adresse->setUsers($currentUser);
          $entityManager->persist($adresse);
          $entityManager->flush();
          return $this->redirectToRoute('app_profil');
      }

        return $this->render('profil/addresses.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/profil/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(UsersForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('users/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/change-password', name: 'app_change_password', methods: ['GET', 'POST'])]
    public function changePassword(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        $form = $this->createForm(ChangePasswordForm::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            // Vérifier le mot de passe actuel
            if (!$passwordHasher->isPasswordValid($user, $currentPassword)) {
                $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
            } else {
                // Hasher et sauvegarder le nouveau mot de passe
                $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                $user->setPassword($hashedPassword);

                $entityManager->flush();

                $this->addFlash('success', 'Votre mot de passe a été modifié avec succès.');
                return $this->redirectToRoute('app_profil', ['password_changed' => true]); // ou votre route de profil
            }
        }

        return $this->render('users/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
