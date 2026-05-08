@extends('layouts.talk-app')

@section('content')
    <x-title>Mocking in Laravel</x-title>

    <x-small-title>
        Replace dependencies cleanly
    </x-small-title>

    <x-body>
        <x-p>
            Laravel makes mocking easy with built-in helpers.
        </x-p>

        <x-p>
            <strong>The service:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Services;

use App\Contracts\PaymentGateway;

class CheckoutService
{
    public function __construct(
        protected PaymentGateway $gateway
    ) {}

    public function process(Order $order): array
    {
        return $this->gateway->charge(
            $order->total,
            $order->payment_token
        );
    }
}
        </x-code>

        <x-p>
            <strong>The test with mock:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Unit\Services;

use App\Contracts\PaymentGateway;
use App\Models\Order;
use App\Services\CheckoutService;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CheckoutServiceTest extends TestCase
{
    #[Test]
    public function order_is_charged_through_gateway(): void
    {
        $gateway = $this->createMock(PaymentGateway::class);

        $gateway->expects($this->onc())
            ->method('charge')
            ->with(100, 'tok_visa')
            ->willReturn([
                'status' => 'succeeded',
                'id' => 'ch_123',
            ]);

        $service = new CheckoutService($gateway);

        $order = Order::factory()->make([
            'total' => 100,
            'payment_token' => 'tok_visa',
        ]);

        $result = $service->process($order);

        $this->assertSame('succeeded', $result['status']);
    }
}
        </x-code>

        <x-p>
            <strong>Why mock?</strong>
        </x-p>

        <x-ul>
            <li>Tests run fast — no real API calls</li>
            <li>Tests are deterministic — no network failures</li>
            <li>Tests are isolated — failures point to your code</li>
        </x-ul>
    </x-body>
@endsection
