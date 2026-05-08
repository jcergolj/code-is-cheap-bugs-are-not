<?php

namespace App\Http\Controllers;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private PaymentGateway $gateway,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'total' => ['required', 'integer', 'min:1'],
            'payment_token' => ['required', 'string'],
        ]);

        $order = Order::create($validated);

        $result = $this->gateway->charge($order->total, $order->payment_token);

        if ($result['status'] === 'succeeded') {
            $order->update(['status' => 'paid']);
        }

        return response()->json([
            'order_id' => $order->id,
            'status' => $result['status'],
            'amount' => $result['amount'],
        ]);
    }
}
