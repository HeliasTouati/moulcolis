<?php
namespace App\Controller;

use App\Entity\Addresses;
use App\Form\AddressesForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AddressController extends AbstractController
{
    #[Route('/address/new', name: 'app_address_new')]
    #[IsGranted('ROLE_USER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Créer une nouvelle adresse
        $address = new Addresses();

        // Récupérer l'utilisateur connecté
        $currentUser = $this->getUser();

        // Créer le formulaire en passant l'utilisateur courant
        $form = $this->createForm(AddressesForm::class, $address, [
            'current_user' => $currentUser
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associer automatiquement l'utilisateur connecté à l'adresse
            $address->setUsers($currentUser);

            $entityManager->persist($address);
            $entityManager->flush();

            $this->addFlash('success', 'Votre adresse a été ajoutée avec succès !');

            return $this->redirectToRoute('app_user_addresses'); // ou votre route de choix
        }

        return $this->render('address/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/address/{id}/edit', name: 'app_address_edit')]
    #[IsGranted('ROLE_USER')]
    public function edit(Request $request, Addresses $address, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur connecté est propriétaire de l'adresse
        if ($address->getUsers() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas modifier cette adresse.');
        }

        $form = $this->createForm(AddressesForm::class, $address, [
            'current_user' => $this->getUser()
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Votre adresse a été modifiée avec succès !');

            return $this->redirectToRoute('app_user_addresses');
        }

        return $this->render('address/edit.html.twig', [
            'form' => $form->createView(),
            'address' => $address,
        ]);
    }

    #[Route('/my-addresses', name: 'app_user_addresses')]
    #[IsGranted('ROLE_USER')]
    public function myAddresses(): Response
    {
        $user = $this->getUser();
        $addresses = $user->getAddresses(); // Assuming you have this method in Users entity

        return $this->render('address/my_addresses.html.twig', [
            'addresses' => $addresses,
        ]);
    }
}
