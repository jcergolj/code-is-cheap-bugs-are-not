@extends('layouts.talk-app')

@section('content')
    <x-title>Structure of Tests</x-title>

    <x-small-title>
        A consistent pattern for every test class
    </x-small-title>

    <x-body>
        <x-p>
            Things are predictable and easy to scan.
        </x-p>

        <x-code language="php" dataLine="3, 6, 9, 13, 20, 23, 37">
// tests/Feature/Http/Controllers/UserController/CeateTest.php

// 1. App\Http\Controllers\UserController -> namespace matches
namespace Tests\Feature\Http\Controllers\UserController;

// 2. which class is tested
#[CoversClass(UserController::class)]

// 3. which method is tested
#[CoversMethod(UserController::class, 'Create')]
class CreateTest extends TestCase
{
    // 4. param that can be used throughout
    public User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // 5. creating local param
        $this->user = User::factory()->create();

        // 6. assign all fakes here
        Queue::fake();
        Mail::fake();
    }

    #[Test]
    public function foo(): void
    {
        //...
    }

    #[Test]
    public function bar(): void
    {
        // 7. update user if needed in the arrange phase
        $this->user->update(['active' => false]);
        //...
    }
}
        </x-code>

        <x-p>
            Seven steps. Same order. Every time.
        </x-p>
    </x-body>
@endsection
