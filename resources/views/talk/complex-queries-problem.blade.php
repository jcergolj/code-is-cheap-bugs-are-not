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
            <strong>The naive approach — everything in controller:</strong>
        </x-p>

        <x-code language="php">
public function index(Request $request)
{
    $results = User::search($request)
        ->onlyEditor()
        ->filterByStatus($request)
        ->orderBy('created_at', 'desc')
        ->paginate();

    return view('users.index', compact('results'));
}
        </x-code>

        <x-p>
            This works for simple cases, but becomes a nightmare as complexity grows.
        </x-p>
    </x-body>
@endsection
