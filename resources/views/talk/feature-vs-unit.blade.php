@extends('layouts.talk-app')

@section('content-center')
    <x-title>Feature, Unit, and the Missing Integration</x-title>

    <x-small-title>
        Controllers and commands get feature tests. Everything else gets unit tests.
    </x-small-title>

    <x-body>
        <x-p>
            Integration tests? This is not how we write test in Laravel.
        </x-p>

        <x-p>
            But why? Because Taylor said so.
        </x-p>
    </x-body>
@endsection
