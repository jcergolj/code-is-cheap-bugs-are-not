@extends('layouts.talk-app')

@section('content')
    <x-title>The Testing Pattern</x-title>

    <x-small-title>
        Feature test checks it's wired up. Unit test checks the logic.
    </x-small-title>

    <x-body>
        <div class="flex flex-col justify-center items-center gap-4 my-4 max-w-3xl mx-auto">
            <div class="bg-slate-800 p-4 rounded-lg border-2 border-sky-500 text-center w-full">
                <x-p class="text-lg font-bold text-sky-400 mb-1">FEATURE TEST</x-p>
                <x-p class="text-base text-white mb-2">"Is it used?"</x-p>
                <x-code language="php">
$response->assertMiddlewareIsApplied('auth');

$this->assertContainsFormRequest(StoreUserRequest::class);

Event::assertDispatched(OrderPlaced::class);

Queue::assertPushed(ProcessOrder::class);
                </x-code>
            </div>

            <div class="text-2xl text-slate-500">+</div>

            <div class="bg-slate-800 p-4 rounded-lg border-2 border-amber-500 text-center w-full">
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
