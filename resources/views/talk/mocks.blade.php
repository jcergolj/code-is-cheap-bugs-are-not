@extends('layouts.talk-app')

@section('content')
    <x-title>Mocks: Asserting a Class is Used</x-title>

    <x-small-title>
        When Laravel doesn't give you a native assertion
    </x-small-title>

    <x-body>
        <x-p>
            What can we do when Laravel doesn't provide a native assertion? We can use mocks.
        </x-p>

        <x-section-label>Controller</x-section-label>

        <x-code language="php">
// app/Http/Controllers/UserController.php
public function store(CreateUserRequest $request, CreateUserAction $action)
{
    $user = $action->execute($request->validated());

    return redirect()->route('users.show', $user);
}
        </x-code>

        <x-section-label>Feature Test</x-section-label>

        <x-code language="php">
// tests/Feature/Http/Controllers/UserController/StoreTest.php
$mock = $this->createMock(CreateUserAction::class);

$mock->expects($this->once())
    ->method('execute')
    ->with($this->anything());

$this->app->instance(CreateUserAction::class, $mock);

$this->post(route('users.store'), $data);
        </x-code>

        <x-p>
            Laravel has no <code>assertActionExecuted()</code>, so we mock <code>CreateUserAction</code> to verify it's called.
        </x-p>
    </x-body>
@endsection
