<?php
// src/Controller/StripeController.php

namespace App\Controller;

use App\Entity\Packages;
use App\Repository\PackagesRepository;
use App\Service\StripeService;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    #[Route("/create-customer", name: "create_customer")]
    public function createCustomer(): Response
    {
        $customer = $this->stripeService->createCustomer([
            'email' => 'customer@example.com',
            'source' => 'tok_mastercard',
        ]);

        return $this->json($customer);
    }

    #[Route("/create-charge", name: "create_charge")]
    public function createCharge(): Response
    {
        $charge = $this->stripeService->createCharge([
            'amount' => 1000,
            'currency' => 'usd',
            'customer' => 'cus_123456789',
        ]);

        return $this->json($charge);
    }


    public function checkout(
        Request $request,
        PackagesRepository $packagesRepository,
        UrlGeneratorInterface $urlGenerator
    ): RedirectResponse
    {
        // Récupère l'ID du colis depuis le formulaire ou la requête
        $packageId = $request->request->get('packageId');
        $package = $packagesRepository->find($packageId);


        if (!$package) {
            throw $this->createNotFoundException('Colis non trouvé');
        }

        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $lineItems = [[
            'price_data' => [
                'currency'     => 'eur',
                'unit_amount'  => $package->getPrice(),
                'product_data' => [
                    'name'        => 'Paiement pour le colis #' . $package->getId(),
                    'description' => 'Description de votre colis ou service.',
                ],
            ],
            'quantity'   => 1,
        ]];

        try {
            $session = Session::create([
                'payment_method_types' => ['card', 'bancontact', 'ideal'],
                'line_items'           => $lineItems,
                'mode'                 => 'payment',
                'success_url'          => $urlGenerator->generate('payment_success', ['id' => $package->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url'           => $urlGenerator->generate('payment_cancel', ['id' => $package->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            ]);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la création de la session de paiement : ' . $e->getMessage());
            return $this->redirectToRoute('app_packages_show', ['id' => $package->getId()]);
        }

        return new RedirectResponse($session->url);
    }

    #[Route('/stripe/success', name: 'payment_success', methods: ['GET'])]
    public function paymentSuccess(): Response
    {
        return $this->render('stripe/success.html.twig');
    }

    #[Route('/stripe/cancel', name: 'payment_cancel', methods: ['GET'])]
    public function paymentCancel(): Response
    {
        return $this->render('stripe/cancel.html.twig');
    }

    #[Route('/stripe/webhook', name: 'stripe_webhook', methods: ['POST'])]
    public function webhook(): Response
    {
        // Gestion des webhooks Stripe
        return new Response('Webhook received', 200);
    }
}
