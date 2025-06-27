<?php

namespace App\Controller;

use App\Entity\Addresses;
use App\Form\AddressesForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->render('profil/index.html.twig', [
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


}
