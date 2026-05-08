<?php

namespace App\Services;

use App\Contracts\PaymentGateway;
use App\Models\Order;

class CheckoutService
{
    public function __construct(
        private PaymentGateway $gateway,
    ) {}

    public function process(Order $order): array
    {
        return $this->gateway->charge($order->total, $order->payment_token);
    }
}
