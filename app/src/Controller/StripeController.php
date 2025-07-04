<?php
// src/Controller/StripeController.php

namespace App\Controller;

use App\Service\StripeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripeController extends AbstractController
{
    private StripeService $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * @Route("/create-customer", name="create_customer")
     */
    public function createCustomer(): Response
    {
        $customer = $this->stripeService->createCustomer([
            'email' => 'customer@example.com',
            'source' => 'tok_mastercard',
        ]);

        return $this->json($customer);
    }

    /**
     * @Route("/create-charge", name="create_charge")
     */
    public function createCharge(): Response
    {
        $charge = $this->stripeService->createCharge([
            'amount' => 1000,
            'currency' => 'usd',
            'customer' => 'cus_123456789',
        ]);

        return $this->json($charge);
    }

    #[Route('/stripe/checkout', name: 'stripe_checkout', methods: ['POST'])]
    public function checkout(): Response
    {
        // Logique de checkout
    }

    #[Route('/stripe/success', name: 'stripe_success', methods: ['GET'])]
    public function success(): Response
    {
        // Page de succès
    }

    #[Route('/stripe/cancel', name: 'stripe_cancel', methods: ['GET'])]
    public function cancel(): Response
    {
        // Page d'annulation
    }

    #[Route('/stripe/webhook', name: 'stripe_webhook', methods: ['POST'])]
    public function webhook(): Response
    {
        // Gestion des webhooks Stripe
    }

}
