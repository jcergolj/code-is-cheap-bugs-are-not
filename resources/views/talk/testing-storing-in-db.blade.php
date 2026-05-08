@extends('layouts.talk-app')

@section('content')
    <x-title>Storing in Database</x-title>

    <x-small-title>
        assertDatabaseHas vs assertSame
    </x-small-title>

    <x-body>
        <x-p>
            When testing a store method, you want to know the record was created correctly.
        </x-p>

        <x-section-label>Controller</x-section-label>

        <x-code language="php">
// app/Http/Controllers/UserController.php
public function store(StoreUserRequest $request)
{
    $user = User::create($request->validated());

    return redirect()->route('users.show', $user);
}
        </x-code>

        <x-section-label>The vague way</x-section-label>

        <x-code language="php">
// tests/Feature/Http/Controllers/UserController/StoreTest.php
$this->post(route('users.store'), [
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

$this->assertDatabaseHas('users', [
    'email' => 'john@example.com',
    'name' => 'John Doe',
]);
        </x-code>

        <x-section-label>The granular way I prefer</x-section-label>

        <x-code language="php">
// tests/Feature/Http/Controllers/UserController/StoreTest.php
$response = $this->post(route('users.store'), [
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

$user = User::latest()->first();

$this->assertSame('John Doe', $user->name);
$this->assertSame('john@example.com', $user->email);
$this->assertTrue($user->email_verified_at->isToday());

$response->assertRedirect(route('users.show', $user));
        </x-code>

        <x-p>
            Granular assertions tell you exactly what failed. No guessing.
        </x-p>
    </x-body>
@endsection
