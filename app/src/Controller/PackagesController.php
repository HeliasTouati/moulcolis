<?php

namespace App\Controller;

use App\Entity\Packages;
use App\Form\PackagesForm;
use App\Repository\OrdersRepository;
use App\Repository\PackagesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/packages')]
final class PackagesController extends AbstractController
{
    #[Route(name: 'app_packages_index', methods: ['GET'])]
    public function index(PackagesRepository $packagesRepository): Response
    {
        return $this->render('packages/index.html.twig', [
            'packages' => $packagesRepository->findAll(),
        ]);
    }

    #[Route('/new/{orderId}', name: 'app_packages_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $orderId, OrdersRepository $ordersRepository): Response
    {
        $package = new Packages();
        $order = $ordersRepository->find($orderId);

        if (!$order) {
            throw $this->createNotFoundException('Commande non trouvée');
        }

        $form = $this->createForm(PackagesForm::class, $package);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $package->setOrders($order);

            $entityManager->persist($package);
            $entityManager->flush();

            return $this->redirectToRoute('app_payment', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('packages/new.html.twig', [
            'package' => $package,
            'form' => $form,
            'order' => $order,
        ]);
    }

    #[Route('/{id}', name: 'app_packages_show', methods: ['GET'])]
    public function show(Packages $package): Response
    {
        return $this->render('packages/show.html.twig', [
            'package' => $package,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_packages_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Packages $package, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PackagesForm::class, $package);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_packages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('packages/edit.html.twig', [
            'package' => $package,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_packages_delete', methods: ['POST'])]
    public function delete(Request $request, Packages $package, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$package->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($package);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_packages_index', [], Response::HTTP_SEE_OTHER);
    }
}
