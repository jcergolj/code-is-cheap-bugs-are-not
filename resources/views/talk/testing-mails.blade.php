@extends('layouts.talk-app')

@section('content')
    <x-title>Testing Mails</x-title>

    <x-small-title>
        Don't actually send emails in tests
    </x-small-title>

    <x-body>
        <x-p>
            Use Mail::fake() to assert emails are queued without sending them.
        </x-p>

        <x-p>
            <strong>The mailable:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;

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
                'order' => $this->order,
                'trackingUrl' => "https://example.com/track/{$this->order->tracking_number}",
            ],
        );
    }
}
        </x-code>

        <x-p>
            <strong>The controller:</strong>
        </x-p>

        <x-code language="php">
use Illuminate\Support\Facades\Mail;

public function store(StoreOrderRequest $request)
{
    $order = Order::create($request->validated());

    Mail::to($order->email)
        ->send(new OrderConfirmation($order));

    return redirect()->route('orders.show', $order);
}
        </x-code>

        <x-p>
            <strong>Feature test — assert mail is sent:</strong>
        </x-p>

        <x-code language="php">
use Illuminate\Support\Facades\Mail;

Mail::fake();

$this->post(route('orders.store'), $data);

Mail::assertOutgoingCount(1);

Mail::assertQueued(OrderConfirmation::class, function ($mail) {
    $this->assertTrue($mail->hasTo('john@example.com'));
    $this->assertSame('Order #1 Confirmed', $mail->subject);

    return true;
});
        </x-code>

        <x-p>
            <strong>Unit test — what you can test:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Unit\Mail;

use App\Mail\OrderConfirmation;
use App\Models\Order;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

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
    public function mail_has_correct_sender(): void
    {
        $order = Order::factory()->create();

        $mail = new OrderConfirmation($order);

        $this->assertTrue($mail->hasFrom('shop@example.com'));
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
    public function mail_uses_markdown_view(): void
    {
        $order = Order::factory()->create();

        $mail = new OrderConfirmation($order);

        $this->assertSame(
            'emails.order-confirmation',
            $mail->content()->markdown
        );
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
