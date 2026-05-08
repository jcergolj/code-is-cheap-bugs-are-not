@extends('layouts.talk-app')

@section('content')
    <div class="flex items-center gap-3 mb-2">
        <span class="bg-[#0284c7] text-white text-sm font-bold px-3 py-1 rounded-full">Part 4 of 4</span>
        <span class="text-[#64748b] text-sm font-medium">Solving Complex Query Testing</span>
    </div>

    <x-title>4/4 Mocking Eloquent Models</x-title>

    <x-small-title>
        Partial mock for complex chains
    </x-small-title>

    <x-body>
        <x-p>
            No extra classes needed — mock the model directly.
        </x-p>

        <x-p>
            <strong>The controller:</strong>
        </x-p>

        <x-code language="php">
public function index(Request $request, User $user)
{
    $results = $user->search($request)
        ->onlyEditor()
        ->filterByStatus($request)
        ->orderBy('created_at', 'desc')
        ->paginate();

    return view('users.index', compact('results'));
}
        </x-code>

        <x-p>
            <strong>Feature test — partial mock:</strong>
        </x-p>

        <x-code language="php">
use Mockery;

$this->partialMock(User::class, function ($mock) {
    $mock->shouldReceive('search')
        ->with(Request::class)
        ->once()
        ->andReturn($mock);

    $mock->shouldReceive('onlyEditor')
        ->once()
        ->andReturn($mock);

    $mock->shouldReceive('filterByStatus')
        ->once()
        ->andReturn($mock);

    $mock->shouldReceive('orderBy')
        ->once()
        ->andReturn($mock);

    $mock->shouldReceive('paginate')
        ->once()
        ->andReturn(User::paginate());
});

$response = $this->get('/users');

$response->assertViewIs('users.index');
        </x-code>

        <x-p>
            <strong>Unit test — test each scope:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserTest extends TestCase
{
    #[Test]
    public function search_filters_by_name(): void
    {
        $user1 = User::factory()->create(['name' => 'John']);
        User::factory()->create(['name' => 'Jane']);

        $results = User::search('John')->get();

        $this->assertCount(1, $results);
        $this->assertTrue($results->contains($user1));
    }

    #[Test]
    public function only_editor_filters_by_role(): void
    {
        User::factory()->create(['role' => 'editor']);
        User::factory()->create(['role' => 'user']);

        $this->assertCount(1, User::onlyEditor()->get());
    }
}
        </x-code>

        <x-p>
            <strong>Don't be ashamed:</strong>
        </x-p>

        <x-p>
            Not everyone loves repositories or mocking. But when queries get complex, these tools make testing manageable.
        </x-p>
    </x-body>
@endsection
