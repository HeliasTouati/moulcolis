<?php

namespace App\Controller;

use App\Entity\Orders;
use App\Form\OrdersForm;
use App\Repository\OrdersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur pour la gestion des commandes
 * Préfixe de route : /orders
 */
#[Route('/orders')]
final class OrdersController extends AbstractController
{
    /**
     * Affiche la liste de toutes les commandes
     * Route : GET /orders
     */
    #[Route(name: 'app_orders_index', methods: ['GET'])]
    public function index(OrdersRepository $ordersRepository): Response
    {
        // Récupère toutes les commandes depuis la base de données
        // et les passe à la vue Twig pour affichage
        return $this->render('orders/index.html.twig', [
            'orders' => $ordersRepository->findAll(),
        ]);
    }

    /**
     * Création d'une nouvelle commande
     * Route : GET/POST /orders/new
     */
    #[Route('/new', name: 'app_orders_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Crée une nouvelle instance de commande
        $order = new Orders();

        // Récupère l'utilisateur actuellement connecté
        $currentUser = $this->getUser();

        // Associe l'utilisateur à la commande
        $order->setUsers($currentUser);

        // Crée le formulaire basé sur OrdersForm
        $form = $this->createForm(OrdersForm::class, $order);

        // Traite la requête HTTP (récupère les données du formulaire si POST)
        $form->handleRequest($request);

        // Vérifie si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {

            // Prépare l'entité pour la sauvegarde
            $entityManager->persist($order);

            // Exécute la sauvegarde en base de données
            $entityManager->flush();

            // Redirige vers la création de packages avec l'ID de la commande
            // Indique un workflow : Commande -> Packages
            return $this->redirectToRoute('app_packages_new', ['orderId' => $order->getId()]);
        }

        // Affiche le formulaire de création (GET) ou en cas d'erreur de validation
        return $this->render('orders/new.html.twig', [
            'order' => $order,
            'form' => $form,
        ]);
    }
}
