@extends('layouts.talk-app')

@section('content')
    <div class="flex items-center gap-3 mb-2">
        <span class="bg-[#0284c7] text-white text-sm font-bold px-3 py-1 rounded-full">Part 1 of 4</span>
        <span class="text-[#64748b] text-sm font-medium">Solving Complex Query Testing</span>
    </div>

    <x-title>1/4 The Problem</x-title>

    <x-small-title>
        Complex queries are hard to test
    </x-small-title>

    <x-body>
        <x-p>
            Imagine a controller with this query chain:
        </x-p>

        <x-code language="php">
$results = User::search($request)
    ->onlyEditor()
    ->filterByStatus($request)
    ->orderBy('created_at', 'desc')
    ->paginate();
        </x-code>

        <x-p>
            Testing this in a feature test means:
        </x-p>

        <x-ul>
            <li>Creating users with different roles, statuses, and dates</li>
            <li>Testing search functionality across multiple fields</li>
            <li>Verifying pagination works</li>
            <li>One small change breaks everything</li>
        </x-ul>

        <x-p>
            <strong>Feature testing this is cumbersome:</strong>
        </x-p>

        <x-code language="php">
public function test_index_returns_only_editors()
{
    // ...
}

public function test_index_filters_by_active_status()
{
    // ...
}

public function test_index_filters_by_inactive_status()
{
    // ...
}

public function test_index_searches_by_name()
{
    // ...
}

public function test_index_searches_by_email()
{
    // ...
}

public function test_index_orders_by_created_at_desc()
{
    // ...
}

public function test_index_paginates_results()
{
    // ...
}

public function test_index_combines_editor_and_active_filters()
{
    // ...
}
        </x-code>

        <x-p>
            Every new scope or filter multiplies your test cases. One small change breaks everything.
        </x-p>
    </x-body>
@endsection
