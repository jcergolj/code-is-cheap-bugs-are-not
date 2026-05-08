@extends('layouts.talk-app')

@section('content')
    <x-title>Testing Jobs</x-title>

    <x-small-title>
        Queue it and forget it
    </x-small-title>

    <x-body>
        <x-p>
            Jobs move heavy work to the background. Test that they dispatch and run correctly.
        </x-p>

        <x-p>
            <strong>The job:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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

        <x-p>
            <strong>Feature test — assert job is dispatched:</strong>
        </x-p>

        <x-code language="php">
use Illuminate\Support\Facades\Queue;

Queue::fake();

$this->post(route('orders.store'), $data);

Queue::assertPushed(ProcessOrder::class, function ($job) use ($order) {
    $this->assertTrue($job->order->is($order));

    return true;
});
        </x-code>

        <x-p>
            <strong>Unit test — assert job logic:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Unit\Jobs;

use App\Jobs\ProcessOrder;
use App\Models\Order;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

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
