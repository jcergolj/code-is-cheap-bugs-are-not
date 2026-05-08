@extends('layouts.talk-app')

@section('content')
    <x-title>Jobs</x-title>

    <x-small-title>
        Queue it and forget it
    </x-small-title>

    <x-body>
        <x-p>
            Jobs move heavy work to the background. Test that they dispatch and run correctly.
        </x-p>

        <x-section-label>Job</x-section-label>

        <x-code language="php">
// app/Jobs/ProcessOrder.php
class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Order $order) {}

    public function handle(): void
    {
        $this->order->update(['status' => 'completed']);
    }
}
        </x-code>

        <x-section-label>Controller</x-section-label>

        <x-code language="php">
// app/Http/Controllers/OrderController.php
public function store(StoreOrderRequest $request)
{
    $order = Order::create($request->validated());

    ProcessOrder::dispatch($order);

    // ...
}
        </x-code>

        <x-section-label>Feature Test — assert job is dispatched</x-section-label>

        <x-code language="php" dataLine="2, 6-12">
// tests/Feature/Http/Controllers/OrderController/StoreTest.php
Queue::fake();

$this->post(route('orders.store'), $data);

Queue::assertPushed(ProcessOrder::class, function ($job) use ($order) {
    // return $job->order->is($order) && $job->order-> isInstanceOf(Order::class)
    $this->assertTrue($job->order->is($order));
    $this->assertTrue($job->order-> isInstanceOf(Order::class));

    return true;
});
        </x-code>

        <x-section-label>Unit Test — assert job logic</x-section-label>

        <x-code language="php">
// tests/Unit/Jobs/ProcessOrderTest.php
class ProcessOrderTest extends TestCase
{
    #[Test]
    public function order_status_is_updated_to_completed(): void
    {
        $order = Order::factory()->create(['status' => 'pending']);

        ProcessOrder::dispatch($order);

        $this->assertSame('completed', $order->fresh()->status);
    }
}
        </x-code>
    </x-body>
@endsection
