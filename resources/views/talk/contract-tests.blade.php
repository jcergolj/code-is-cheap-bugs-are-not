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
            You write a fake payment gateway for fast tests. Months later, the real API changes. Your tests still pass, but production breaks.
        </x-p>

        <img src="/images/contract-test-diagram.png" alt="Contract Test Pattern Diagram" class="w-full rounded-lg shadow-md" />

        <x-section-label>The Contract</x-section-label>

        <x-code language="php">
// app/Contracts/PaymentGateway.php
interface PaymentGateway
{
    public function charge(int $amount, string $token): array;
}
        </x-code>

        <x-section-label>The Fake (fast, for unit tests)</x-section-label>

        <x-code language="php">
// app/Services/Payment/FakePaymentGateway.php
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
}
        </x-code>

        <x-section-label>The Real Implementation (slow, hits API)</x-section-label>

        <x-code language="php">
// app/Services/Payment/StripePaymentGateway.php
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
}
        </x-code>

        <x-section-label>The Contract Test — data provider approach</x-section-label>

        <x-code language="php">
// tests/Contracts/PaymentGatewayContractTest.php
class PaymentGatewayContractTest extends TestCase
{
    #[Test]
    #[DataProvider('gatewaysProviders')]
    public function charges_with_a_valid_payment_token_are_successful($paymentGateway)
    {
        $charge = $paymentGateway->charge(2500, $paymentGateway->getValidToken());

        $this->assertSame(2500, $charge);
    }

    #[Test]
    #[DataProvider('gatewaysProviders')]
    public function charges_with_an_invalid_payment_token_fail($paymentGateway)
    {
        try {
            $paymentGateway->charge(2500, 'invalid-payment-token');
        } catch (Exception $e) {
            $this->assertTrue(true);
            return;
        }

        $this->fail('Charging with an invalid payment token did not throw a PaymentFailedException.');
    }

    public function gatewaysProviders()
    {
        return [
            'Fake payment gateway' => [new FakePaymentGateway()],
            'Stripe payment gateway' => [new StripePaymentGateway('secret-stripe-key')],
        ];
    }
}
        </x-code>

        <x-section-label>Run only fast tests</x-section-label>

        <x-code language="bash">
# Run only the fake variant
php artisan test --filter='gatewaysProviders.*Fake'

# Run only the stripe variant
php artisan test --filter='gatewaysProviders.*Stripe'
        </x-code>

        <x-read-more href="https://jcergolj.me.uk/blog/contract-tests">
            Contract Tests
        </x-read-more>
    </x-body>
@endsection
