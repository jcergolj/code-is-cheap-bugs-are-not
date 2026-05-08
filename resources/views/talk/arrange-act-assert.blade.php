@extends('layouts.talk-app')

@section('content')
    <x-title>Arrange, Act, Assert</x-title>

    <x-small-title>
        Every test follows the same three-step dance.
    </x-small-title>

    <x-body>
        <x-section-label>Arrange — set up the world</x-section-label>

        <x-code language="php">
$user = User::factory()->create(['name' => 'John']);
        </x-code>

        <x-section-label>Act — do the thing</x-section-label>

        <x-code language="php">
$response = $this->get('/users');
        </x-code>

        <x-section-label>Assert — check the result</x-section-label>

        <x-code language="php">
$response->assertOk();

$this->assertDatabaseHas('users', [
    'name' => 'John Doe',
    'email' => 'john@example.com',
]);
        </x-code>
    </x-body>
@endsection
