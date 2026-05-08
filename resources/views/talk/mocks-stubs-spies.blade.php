@extends('layouts.talk-app')

@section('content')
    <x-title>Mocks&amp; Spies</x-title>

    <x-small-title>
        Know your test doubles
    </x-small-title>

    <x-body>
        <x-p>
            <strong>Mock — asserts expectations:</strong>
        </x-p>

        <x-p>
            Use when you need to verify a method was called with specific arguments.
        </x-p>

        <x-code language="php">
$mock = $this->createMock(EmailService::class);

$mock->expects($this->onc())
    ->method('send')
    ->with('john@example.com', 'Welcome!');

$service = new UserService($mock);
$service->register('john@example.com');
        </x-code>

        <x-p>
            <strong>Spy — records calls for later assertion:</strong>
        </x-p>

        <x-p>
            Use when you want to verify behavior after the fact.
        </x-p>

        <x-code language="php">
$spy = $this->createMock(Logger::class);

$spy->expects($this->any())
    ->method('info');

$service = new OrderService($spy);
$service->process($order);

// Assert later
$spy->expects($this->onc())
    ->method('info')
    ->with('Order processed: 123');
        </x-code>

        <x-p>
            <strong>When to use what:</strong>
        </x-p>

        <x-ul>
            <li><strong>Mock</strong> — you need to verify interactions</li>
            <li><strong>Spy</strong> — you want to inspect calls after execution</li>
        </x-ul>
    </x-body>
@endsection
