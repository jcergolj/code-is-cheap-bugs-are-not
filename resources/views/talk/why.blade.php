@extends('layouts.talk-app')

@section('content-center')
    <x-title>Why?</x-title>

    <x-small-title>
        I test so I can sleep at night.
    </x-small-title>

    <x-body>
        <x-p class="text-4xl md:text-5xl font-bold text-amber-600 mt-4 mb-1">
            But it doesn't have to be like that.
        </x-p>

        <x-p class="text-3xl md:text-4xl font-semibold text-sky-600 mt-1 mb-4">
            So what can we do about it?
        </x-p>

        <x-p>
            We automate:
        </x-p>

        <x-ul>
            <x-li>Does the code actually do what it's supposed to do?</x-li>
            <x-li>Is it wired up correctly?</x-li>
            <x-li>Can I upgrade Laravel without fear?</x-li>
        </x-ul>

        <x-p class="text-2xl md:text-3xl text-[#64748b] mt-8 italic line-through">
            We don't have time nor money to write tests.
        </x-p>
    </x-body>
@endsection
