<?php

namespace App\Services\Payment;

use App\Contracts\PaymentGateway;

class FakePaymentGateway implements PaymentGateway
{
    public function charge(int $amount, string $token): array
    {
        return [
            'id' => 'ch_'.uniqid(),
            'status' => 'succeeded',
            'amount' => $amount,
        ];
    }
}
