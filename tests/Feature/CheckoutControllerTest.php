<?php

namespace Tests\Feature;

use App\Contracts\PaymentGateway;
use App\Services\Payment\FakePaymentGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

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

    #[Test]
    public function it_validates_required_fields(): void
    {
        $response = $this->postJson('/checkout', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['total', 'payment_token']);
    }

    #[Test]
    public function total_must_be_at_least_one(): void
    {
        $response = $this->postJson('/checkout', [
            'total' => 0,
            'payment_token' => 'tok_visa',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['total']);
    }
}
