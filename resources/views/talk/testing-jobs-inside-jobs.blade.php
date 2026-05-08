@extends('layouts.talk-app')

@section('content')
    <x-title>Testing Jobs Inside Jobs</x-title>

    <x-small-title>
        When one job triggers another
    </x-small-title>

    <x-body>
        <x-p>
            Sometimes a job dispatches another job. Test both independently.
        </x-p>

        <x-p>
            <strong>The parent job:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable;

    public function __construct(public Order $order) {}

    public function handle(): void
    {
        $this->order->update(['status' => 'processing']);

        UpdateStorage::dispatch($this->order);
    }
}
        </x-code>

        <x-p>
            <strong>The child job:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateStorage implements ShouldQueue
{
    use Dispatchable;

    public function __construct(public Order $order) {}

    public function handle(): void
    {
        $this->order->storage->increment('item_count');
    }
}
        </x-code>

        <x-p>
            <strong>Feature test — assert child job is dispatched:</strong>
        </x-p>

        <x-code language="php">
use Illuminate\Support\Facades\Queue;

Queue::fake();

$this->post(route('orders.store'), $data);

Queue::assertPushed(ProcessOrder::class);
Queue::assertPushed(UpdateStorage::class);
        </x-code>

        <x-p>
            <strong>Unit test — assert parent dispatches child:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Unit\Jobs;

use App\Jobs\ProcessOrder;
use App\Jobs\UpdateStorage;
use App\Models\Order;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProcessOrderTest extends TestCase
{
    #[Test]
    public function child_job_is_dispatched(): void
    {
        Queue::fake(UpdateStorage::class);

        $order = Order::factory()->create();

        $job = new ProcessOrder($order);
        $job->handle();

        Queue::assertPushed(UpdateStorage::class, function ($job) use ($order) {
            $this->assertTrue($job->order->is($order));

            return true;
        });
    }
}
        </x-code>
    </x-body>
@endsection
