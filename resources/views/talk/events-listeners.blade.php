@extends('layouts.talk-app')

@section('content')
    <x-title>Events & Listeners</x-title>

    <x-small-title>
        Decouple with events
    </x-small-title>

    <x-body>
        <x-p>
            Events let you react to things without cluttering your controllers.
        </x-p>

        <x-section-label>Event</x-section-label>

        <x-code language="php">
// app/Events/OrderPlaced.php
class OrderPlaced
{
    use Dispatchable, SerializesModels;

    public function __construct(public Order $order) {}
}
        </x-code>

        <x-section-label>Controller</x-section-label>

        <x-code language="php">
// app/Http/Controllers/OrderController.php
public function store(StoreOrderRequest $request)
{
    $order = Order::create($request->validated());

    OrderPlaced::dispatch($order);

    // ...
}
        </x-code>

        <x-section-label>Listener</x-section-label>

        <x-code language="php">
// app/Listeners/UpdateOrderStatus.php
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

        <x-section-label>Feature Test — assert event is dispatched</x-section-label>

        <x-code language="php" dataLine="2, 6">
// tests/Feature/Http/Controllers/OrderController/StoreTest.php
Event::fake();

$this->post(route('orders.store'), $data);

Event::assertDispatched(OrderPlaced::class);
        </x-code>

        <x-section-label>Unit Test — assert listener logic</x-section-label>

        <x-code language="php" dataLine="7-10">
// tests/Unit/Listeners/UpdateOrderStatusTest.php
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
