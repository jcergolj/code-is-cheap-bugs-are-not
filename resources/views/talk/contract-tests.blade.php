@extends('layouts.talk-app')

@section('content')
    <x-title>Contract Tests</x-title>

    <x-small-title>
        Fake and real, tested the same way
    </x-small-title>

    <x-body>
        <x-p>
            When you fake external APIs, how do you know the fake still matches the real thing?
        </x-p>

        <x-p>
            <strong>The problem:</strong>
        </x-p>

        <x-p>
            You write a fake payment gateway for fast tests. Months later, the real API changes. Your tests still pass, but production breaks.
        </x-p>

        <x-p>
            <strong>The contract:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Contracts;

interface PaymentGateway
{
    public function charge(int $amount, string $token): array;
    public function refund(string $chargeId): bool;
}
        </x-code>

        <x-p>
            <strong>The fake (fast, for unit tests):</strong>
        </x-p>

        <x-code language="php">
class FakePaymentGateway implements PaymentGateway
{
    public function charge(int $amount, string $token): array
    {
        return [
            'status' => 'succeeded',
            'id' => 'ch_' . uniqid(),
            'amount' => $amount,
        ];
    }

    public function refund(string $chargeId): bool
    {
        return true;
    }
}
        </x-code>

        <x-p>
            <strong>The real implementation (slow, hits API):</strong>
        </x-p>

        <x-code language="php">
class StripePaymentGateway implements PaymentGateway
{
    public function charge(int $amount, string $token): array
    {
        $response = Http::withToken(config('services.stripe.secret'))
            ->post('https://api.stripe.com/v1/charges', [
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $token,
            ]);

        return $response->json();
    }

    public function refund(string $chargeId): bool
    {
        $response = Http::withToken(config('services.stripe.secret'))
            ->post("https://api.stripe.com/v1/refunds", [
                'charge' => $chargeId,
            ]);

        return $response->successful();
    }
}
        </x-code>

        <x-p>
            <strong>Visual diagram:</strong>
        </x-p>

        <x-p>
            <pre class="text-sm bg-gray-100 p-4 rounded-lg overflow-x-auto">
┌─────────────────────────────────────────────────────────────┐
│                    Contract Test Pattern                     │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│   ┌──────────────┐         ┌──────────────┐                │
│   │   Contract   │◄────────│  Same Tests  │                │
│   │  Interface   │         │   Run Twice  │                │
│   └──────┬───────┘         └──────────────┘                │
│          │                                                  │
│    ┌─────┴─────┐                                            │
│    │           │                                            │
│    ▼           ▼                                            │
│ ┌────────┐  ┌────────┐                                      │
│ │  Fake  │  │  Real  │                                      │
│ │Gateway │  │Gateway │                                      │
│ │(fast)  │  │(slow)  │                                      │
│ └───┬────┘  └───┬────┘                                      │
│     │           │                                           │
│     ▼           ▼                                           │
│  ┌─────┐     ┌─────────┐                                   │
│  │ Pass│     │  Pass   │  ◄── Both must behave identically │
│  └─────┘     └─────────┘                                   │
│                                                             │
└─────────────────────────────────────────────────────────────┘
            </pre>
        </x-p>

        <x-p>
            <strong>The contract test — trait approach:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Contracts;

trait PaymentGatewayContractTests
{
    abstract protected function getGateway(): PaymentGateway;

    #[Test]
    public function charge_returns_successful_response(): void
    {
        $gateway = $this->getGateway();

        $charge = $gateway->charge(2500, 'valid-token');

        $this->assertArrayHasKey('id', $charge);
        $this->assertArrayHasKey('status', $charge);
        $this->assertSame(2500, $charge['amount']);
    }

    #[Test]
    public function charge_fails_with_invalid_token(): void
    {
        $gateway = $this->getGateway();

        $this->expectException(PaymentFailedException::class);

        $gateway->charge(2500, 'invalid-token');
    }

    #[Test]
    public function refund_returns_true_for_valid_charge(): void
    {
        $gateway = $this->getGateway();

        $charge = $gateway->charge(1000, 'valid-token');
        $refunded = $gateway->refund($charge['id']);

        $this->assertTrue($refunded);
    }
}
        </x-code>

        <x-p>
            <strong>Test the fake:</strong>
        </x-p>

        <x-code language="php">
class FakePaymentGatewayTest extends TestCase
{
    use PaymentGatewayContractTests;

    protected function getGateway(): PaymentGateway
    {
        return new FakePaymentGateway;
    }
}
        </x-code>

        <x-p>
            <strong>Test the real implementation:</strong>
        </x-p>

        <x-code language="php">
#[Group('integration')]
class StripePaymentGatewayTest extends TestCase
{
    use PaymentGatewayContractTests;

    protected function getGateway(): PaymentGateway
    {
        return new StripePaymentGateway(config('services.stripe.secret'));
    }
}
        </x-code>

        <x-p>
            <strong>Run only fast tests:</strong>
        </x-p>

        <x-code language="bash">
# Run all tests except integration
php artisan test --exclude-group=integration

# Run only integration tests
php artisan test --group=integration
        </x-code>

        <x-p>
            Read more: <x-link href="https://jcergolj.me.uk/blog/contract-tests">Contract Tests</x-link>
        </x-p>
    </x-body>
@endsection
