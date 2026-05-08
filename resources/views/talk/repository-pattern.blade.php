@extends('layouts.talk-app')

@section('content')
    <div class="flex items-center gap-3 mb-2">
        <span class="bg-[#0284c7] text-white text-sm font-bold px-3 py-1 rounded-full">Part 2 of 4</span>
        <span class="text-[#64748b] text-sm font-medium">Solving Complex Query Testing</span>
    </div>

    <x-title>2/4 Repository Pattern</x-title>

    <x-small-title>
        Extract query logic into a class
    </x-small-title>

    <x-body>
        <x-p>
            Move the complex query into a dedicated repository class.
        </x-p>

        <x-p>
            <strong>The repository:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function __construct(protected User $model) {}

    public function searchAndFilter($request)
    {
        return $this->model->search($request)
            ->onlyEditor()
            ->filterByStatus($request)
            ->orderBy('created_at', 'desc')
            ->paginate();
    }
}
        </x-code>

        <x-p>
            <strong>The controller:</strong>
        </x-p>

        <x-code language="php">
public function index(Request $request, UserRepository $repository)
{
    $results = $repository->searchAndFilter($request);

    return view('users.index', compact('results'));
}
        </x-code>

        <x-p>
            <strong>Feature test — mock the repository:</strong>
        </x-p>

        <x-code language="php">
use Mockery;

$repository = Mockery::mock(UserRepository::class);
$this->app->instance(UserRepository::class, $repository);

$users = User::factory()->count(3)->create();

$repository->shouldReceive('searchAndFilter')
    ->once()
    ->andReturn($users);

$response = $this->get('/users');

$response->assertViewIs('users.index')
    ->assertViewHas('results', $users);
        </x-code>

        <x-p>
            <strong>Unit test — test the repository:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    #[Test]
    public function search_finds_users_by_name(): void
    {
        $user1 = User::factory()->create(['name' => 'John']);
        $user2 = User::factory()->create(['name' => 'Jane']);

        $repository = new UserRepository(new User);
        $results = $repository->searchAndFilter((object)['search' => 'John']);

        $this->assertTrue($results->contains($user1));
        $this->assertFalse($results->contains($user2));
    }
}
        </x-code>
    </x-body>
@endsection
