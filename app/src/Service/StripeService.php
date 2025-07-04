<?php
// src/Service/StripeService.php

namespace App\Service;

use Stripe\Stripe;

class StripeService
{
    public function __construct(string $stripeSecretKey)
    {
        Stripe::setApiKey($stripeSecretKey);
    }

    public function createCustomer(array $customerData)
    {
        return \Stripe\Customer::create($customerData);
    }

    public function createCharge(array $chargeData)
    {
        return \Stripe\Charge::create($chargeData);
    }

}
