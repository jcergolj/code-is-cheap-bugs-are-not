@extends('layouts.talk-app')

@section('content')
    <div class="flex items-center gap-3 mb-2">
        <span class="bg-[#0284c7] text-white text-sm font-bold px-3 py-1 rounded-full">Part 3 of 4</span>
        <span class="text-[#64748b] text-sm font-medium">Solving Complex Query Testing</span>
    </div>

    <x-title>3/4 Query Objects</x-title>

    <x-small-title>
        Single-purpose query classes
    </x-small-title>

    <x-body>
        <x-p>
            Query objects are like repositories but with a single method.
        </x-p>

        <x-p>
            <strong>The query object:</strong>
        </x-p>

        <x-code language="php">
&lt;?php

namespace App\Queries;

use App\Models\User;

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

        <x-p>
            <strong>The controller:</strong>
        </x-p>

        <x-code language="php">
public function index(Request $request, SearchAndFilterUsersQuery $query)
{
    $results = $query->run($request);

    return view('users.index', compact('results'));
}
        </x-code>

        <x-p>
            <strong>When to use query objects:</strong>
        </x-p>

        <x-ul>
            <li>One-off complex queries</li>
            <li>When you don't need full CRUD operations</li>
            <li>When the query is reusable across controllers</li>
        </x-ul>

        <x-p>
            <strong>Test the same way as repositories:</strong>
        </x-p>

        <x-code language="php">
$query = new SearchAndFilterUsersQuery;
$results = $query->run($request);

$this->assertCount(2, $results);
        </x-code>
    </x-body>
@endsection
