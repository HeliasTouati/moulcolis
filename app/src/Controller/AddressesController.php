<?php

namespace App\Controller;

use App\Entity\Addresses;
use App\Form\Addresses1Form;
use App\Form\AddressesForm;
use App\Repository\AddressesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/addresses')]
final class AddressesController extends AbstractController
{
    #[Route(name: 'app_addresses_index', methods: ['GET'])]
    public function index(AddressesRepository $addressesRepository): Response
    {
        return $this->render('addresses/index.html.twig', [
            'addresses' => $addressesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_addresses_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $address = new Addresses();
        $currentUser = $this->getUser();
        $form = $this->createForm(AddressesForm::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUsers($currentUser);
            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('addresses/new.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_addresses_show', methods: ['GET'])]
    public function show(Addresses $address): Response
    {
        return $this->render('addresses/show.html.twig', [
            'address' => $address,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_addresses_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Addresses $address, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AddressesForm::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('addresses/edit.html.twig', [
            'address' => $address,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_addresses_delete', methods: ['POST'])]
    public function delete(Request $request, Addresses $address, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$address->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($address);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profil', [], Response::HTTP_SEE_OTHER);
    }
}
