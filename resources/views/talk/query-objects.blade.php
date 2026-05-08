@extends('layouts.talk-app')

@section('content')
    <x-title>Complex Queries</x-title>

    <x-small-title>
        When scopes multiply, extract a query object
    </x-small-title>

    <x-body>
        <x-p>
            Imagine a controller with this query chain:
        </x-p>

        <x-code language="php">
// app/Http/Controllers/UserController.php
$results = User::search($request)
    ->onlyEditor()
    ->filterByStatus($request)
    ->orderBy('created_at', 'desc')
    ->paginate();
        </x-code>

        <x-p>
            Testing this in a feature test means creating users with different roles, statuses, and dates. One small change breaks everything.
        </x-p>

        <x-section-label>The Solution: Query Object</x-section-label>

        <x-code language="php">
// app/Queries/SearchAndFilterUsersQuery.php
class SearchAndFilterUsersQuery
{
    public function run($request)
    {
        return User::search($request)
            ->onlyEditor()
            ->filterByStatus($request)
            ->orderBy('created_at', 'desc')
            ->paginate();
    }
}
        </x-code>

        <x-code language="php">
// app/Http/Controllers/UserController.php
public function index(Request $request, SearchAndFilterUsersQuery $query)
{
    $results = $query->run($request);

    return view('users.index', compact('results'));
}
        </x-code>

        <x-section-label>Feature Test — mock the query object</x-section-label>

        <x-code language="php">
// tests/Feature/Http/Controllers/UserController/IndexTest.php
$query = Mockery::mock(SearchAndFilterUsersQuery::class);
$this->app->instance(SearchAndFilterUsersQuery::class, $query);

$users = User::factory()->count(3)->create();

$query->shouldReceive('run')
    ->once()
    ->andReturn($users);

$response = $this->get('/users');

$response->assertViewIs('users.index')
    ->assertViewHas('results', $users);
        </x-code>

        <x-section-label>Unit Test — test the query object</x-section-label>

        <x-code language="php">
// tests/Unit/Queries/SearchAndFilterUsersQueryTest.php
class SearchAndFilterUsersQueryTest extends TestCase
{
    #[Test]
    public function run_finds_users_by_name(): void
    {
        $user1 = User::factory()->create(['name' => 'John']);
        $user2 = User::factory()->create(['name' => 'Jane']);

        $query = new SearchAndFilterUsersQuery;
        $results = $query->run((object)['search' => 'John']);

        $this->assertTrue($results->contains($user1));
        $this->assertFalse($results->contains($user2));
    }
}
        </x-code>
    </x-body>
@endsection
