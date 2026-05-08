@extends('layouts.talk-app')

@section('content')
    <x-title>Mails</x-title>

    <x-small-title>
        Don't actually send emails in tests
    </x-small-title>

    <x-body>
        <x-p>
            Use Mail::fake() to assert emails are queued without sending them.
        </x-p>

        <x-section-label>Mailable</x-section-label>

        <x-code language="php">
// app/Mail/OrderConfirmation.php
class OrderConfirmation extends Mailable
{
    public function __construct(public Order $order) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('shop@example.com', 'Example Shop'),
            subject: "Order #{$this->order->id} Confirmed",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order-confirmation',
            with: [
                'trackingUrl' => "https://example.com/track/{$this->order->tracking_number}",
            ],
        );
    }
}
        </x-code>

        <x-section-label>Controller</x-section-label>

        <x-code language="php">
// app/Http/Controllers/OrderController.php
public function store(StoreOrderRequest $request)
{
    $order = Order::create($request->validated());

    Mail::to($order->email)
        ->send(new OrderConfirmation($order));
    // ...
}
        </x-code>

        <x-section-label>Feature Test — assert mail is sent</x-section-label>

        <x-code language="php" dataLine="2, 6, 8-13">
// tests/Feature/Http/Controllers/OrderController/StoreTest.php
Mail::fake();

$this->post(route('orders.store'), $data);

Mail::assertOutgoingCount(1);

Mail::assertQueued(OrderConfirmation::class, function ($mail) {
    $this->assertTrue($mail->hasTo('john@example.com'));
    $this->assertSame('Order #1 Confirmed', $mail->subject);

    return true;
});
        </x-code>

        <x-section-label>Unit Test — assert mail content</x-section-label>

        <x-code language="php">
// tests/Unit/Mail/OrderConfirmationTest.php
class OrderConfirmationTest extends TestCase
{
    #[Test]
    public function mail_has_correct_subject(): void
    {
        $order = Order::factory()->create();
        $mail = new OrderConfirmation($order);

        $this->assertSame(
            "Order #{$order->id} Confirmed",
            $mail->envelope()->subject
        );
    }

    #[Test]
    public function mail_has_correct_recipient(): void
    {
        $order = Order::factory()->create([
            'email' => 'customer@example.com',
        ]);
        $mail = new OrderConfirmation($order);

        $this->assertTrue($mail->hasTo('customer@example.com'));
    }

    #[Test]
    public function mail_has_tracking_url_in_data(): void
    {
        $order = Order::factory()->create([
            'tracking_number' => 'TRACK123',
        ]);
        $mail = new OrderConfirmation($order);

        $this->assertArrayHasKey('trackingUrl', $mail->content()->with);
        $this->assertStringContainsString(
            'TRACK123',
            $mail->content()->with['trackingUrl']
        );
    }
}
        </x-code>
    </x-body>
@endsection
