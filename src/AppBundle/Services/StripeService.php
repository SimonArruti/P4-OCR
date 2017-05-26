<?php

namespace AppBundle\Services;

use Exception;
use Stripe\Charge;

class StripeService
{
    public function stripe (int $price, string $customer_email, string $token)
    {
        try {
            Charge::create(array(
                "amount" => $price . "00",
                "currency" => "eur",
                "description" => "Louvre tickets bought by " . $customer_email,
                "source" => $token
            ));

            return true;
        }
        catch (Exception $exception) {

            return false;
        }

    }
}