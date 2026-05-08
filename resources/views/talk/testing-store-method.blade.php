@extends('layouts.talk-app')

@section('content')
    <x-title>Testing the Store Method</x-title>

    <x-small-title>
        assertSeeInDatabase vs assertSame
    </x-small-title>

    <x-body>
        <x-p>
            When testing a store method, you want to know the record was created correctly.
        </x-p>

        <x-p>
            <strong>The controller:</strong>
        </x-p>

        <x-code language="php">
public function store(StoreUserRequest $request)
{
    $user = User::create($request->validated());

    return redirect()->route('users.show', $user);
}
        </x-code>

        <x-p>
            <strong>The vague way:</strong>
        </x-p>

        <x-code language="php">
$this->post(route('users.store'), [
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);

$this->assertDatabaseHas('users', [
    'email' => 'john@example.com',
]);
        </x-code>

        <x-p>
            <strong>The granular way I prefer:</strong>
        </x-p>

        <x-code language="php">
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
