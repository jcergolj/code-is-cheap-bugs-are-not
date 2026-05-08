@extends('layouts.talk-app')

@section('content')
    <x-title>Fake Implementations</x-title>

    <x-small-title>
        When mocking is not enough
    </x-small-title>

    <x-body>
        <x-p>
            Mocks are great for isolated unit tests. But sometimes you need a fake that behaves like the real thing.
        </x-p>

        <x-section-label>When to use a fake</x-section-label>

        <x-ul>
            <x-li>The dependency has complex behavior you want to simulate</x-li>
            <x-li>Multiple tests need the same fake setup</x-li>
            <x-li>You want to assert on state, not just method calls</x-li>
            <x-li>The real service is slow, flaky, or costs money</x-li>
        </x-ul>

        <x-section-label>The Contract</x-section-label>

        <x-code language="php">
// app/Contracts/PaymentGateway.php
interface PaymentGateway
{
    public function charge(int $amount, string $token): array;
}
        </x-code>

        <x-section-label>The Fake Implementation</x-section-label>

        <x-code language="php">
            // replaces real implementation in test e.g. StripePaymentGateway
            
// app/Services/Payment/FakePaymentGateway.php
class FakePaymentGateway implements PaymentGateway
{
    public function charge(int $amount, string $token): array
    {
        return [
            'id' => 'ch_' . uniqid(),
            'status' => 'succeeded',
            'amount' => $amount,
        ];
    }
}
        </x-code>

        <x-section-label>The Controller Test with Fake</x-section-label>

        <x-code language="php">
// tests/Feature/CheckoutControllerTest.php
class CheckoutControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->bind(PaymentGateway::class, FakePaymentGateway::class);
    }

    #[Test]
    public function order_is_charged_successfully(): void
    {
        $response = $this->postJson('/checkout', [
            'total' => 100,
            'payment_token' => 'tok_visa',
        ]);

        $response->assertOk()
            ->assertJson([
                'status' => 'succeeded',
                'amount' => 100,
            ]);

        $this->assertDatabaseHas('orders', [
            'total' => 100,
            'payment_token' => 'tok_visa',
            'status' => 'paid',
        ]);
    }
}
        </x-code>

        <x-section-label>Why fakes beat mocks</x-section-label>

        <x-ul>
            <x-li>Track state across multiple calls</x-li>
            <x-li>No fragile mock expectations</x-li>
            <x-li>Reusable across many tests</x-li>
            <x-li>Can be tested with contract tests</x-li>
        </x-ul>

        <x-p>
            Make sure your fake behaves like the real thing. Use contract tests — run the same test suite against both the fake and the real implementation.
        </x-p>

        <x-read-more href="https://jcergolj.me.uk/blog/contract-tests">Contract Tests</x-read-more>
    </x-body>
@endsection
