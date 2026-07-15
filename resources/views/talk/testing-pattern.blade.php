@extends('layouts.talk-app')

@section('content')
    <x-title>The Testing Pattern</x-title>

    <x-small-title>
        Feature test checks it's wired up. Unit test checks the logic.
    </x-small-title>

    <x-body>
        <div class="flex flex-col md:flex-row justify-center items-center gap-4 my-4 max-w-3xl mx-auto">

            <!-- FEATURE TEST -->
            <div class="order-1 bg-slate-800 p-4 rounded-lg border-2 border-sky-500 text-center w-full md:w-1/2 md:mx-2">
                <x-p class="text-lg font-bold text-sky-400 mb-1">FEATURE TEST</x-p>
                <x-p class="text-base text-white mb-2">"Is it used?"</x-p>

                <x-code language="php">
$response->assertMiddlewareIsApplied('auth');

$this->assertContainsFormRequest(StoreUserRequest::class);

Event::assertDispatched(OrderPlaced::class);

Queue::assertPushed(ProcessOrder::class);
                </x-code>
            </div>

            <!-- PLUS -->
            <div class="order-2 text-2xl text-slate-500">
                +
            </div>

            <!-- UNIT TEST -->
            <div class="order-3 bg-slate-800 p-4 rounded-lg border-2 border-amber-500 text-center w-full md:w-1/2">
                <x-p class="text-lg font-bold text-amber-400 mb-1">UNIT TEST</x-p>
                <x-p class="text-base text-white mb-2">"Does it work?"</x-p>

                <x-code language="php">
$instance = new SomeClass;

$result = $instance->handle($data);

$this->assertSame($expected, $result);
                </x-code>
            </div>

        </div>

        <x-p>
            Same pattern, every time. Once you spot it, you can't unsee it.
        </x-p>

        <x-p>
            Next up: middleware, form requests, events, and jobs.
        </x-p>

    </x-body>
@endsection
