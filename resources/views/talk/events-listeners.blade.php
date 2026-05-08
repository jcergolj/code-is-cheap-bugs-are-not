@extends('layouts.talk-app')

@section('content')
    <x-title>Events &amp; Listeners</x-title>

    <x-small-title>
        Decouple with events
    </x-small-title>

    <x-body>
        <x-p>
            Events let you react to things without cluttering your controllers.
        </x-p>

        <x-p>
            <strong>The event:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced
{
    use Dispatchable, SerializesModels;

    public function __construct(public Order $order) {}
}
        </x-code>

        <x-p>
            <strong>The controller that fires it:</strong>
        </x-p>

        <x-code language="php">
public function store(StoreOrderRequest $request)
{
    $order = Order::create($request->validated());

    OrderPlaced::dispatch($order);

    return redirect()->route('orders.show', $order);
}
        </x-code>

        <x-p>
            <strong>The listener:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Listeners;

use App\Events\OrderPlaced;

class UpdateOrderStatus
{
    public function handle(OrderPlaced $event): void
    {
        if ($event->order->total <= 0) {
            $event->order->update(['status' => 'cancelled']);

            return;
        }

        $event->order->update(['status' => 'processing']);
    }
}
        </x-code>

        <x-p>
            <strong>Feature test — assert event is dispatched:</strong>
        </x-p>

        <x-code language="php">
use Illuminate\Support\Facades\Event;

Event::fake();

$this->post(route('orders.store'), $data);

Event::assertDispatched(OrderPlaced::class);
        </x-code>

        <x-p>
            <strong>Unit test — assert listener logic:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Unit\Listeners;

use App\Events\OrderPlaced;
use App\Listeners\UpdateOrderStatus;
use App\Models\Order;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UpdateOrderStatusTest extends TestCase
{
    #[Test]
    public function listener_is_triggered_when_event_is_dispatched(): void
    {
        Event::assertListening(
            OrderPlaced::class,
            UpdateOrderStatus::class
        );
    }

    #[Test]
    public function status_is_processing_when_order_has_total(): void
    {
        $order = Order::factory()->create(['total' => 100]);
        $event = new OrderPlaced($order);
        $listener = new UpdateOrderStatus;

        $listener->handle($event);

        $this->assertSame('processing', $order->fresh()->status);
    }

    #[Test]
    public function status_is_cancelled_when_order_is_free(): void
    {
        $order = Order::factory()->create(['total' => 0]);
        $event = new OrderPlaced($order);
        $listener = new UpdateOrderStatus;

        $listener->handle($event);

        $this->assertSame('cancelled', $order->fresh()->status);
    }
}
        </x-code>
    </x-body>
@endsection
