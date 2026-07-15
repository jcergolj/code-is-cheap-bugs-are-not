@extends('layouts.talk-app')

@section('content-center')
    <x-title>Why do I test?</x-title>

    <x-small-title>
        So I don't get panic calls about payments.
    </x-small-title>

    <x-body>
        <x-ul>
            <x-li>So I know the code does what it's supposed to</x-li>
            <x-li>So I can upgrade Laravel without fear</x-li>
            <x-li>So I can bump packages without breaking everything</x-li>
        </x-ul>

        <x-p class="text-2xl md:text-3xl text-amber-600 mt-8 italic">
            Tests won't stop AWS or Cloudflare from having a bad day. But they will stop you from being the reason for the call.
        </x-p>

        <x-p class="text-2xl md:text-3xl text-sky-600 mt-6 italic">
            "We don't have time or money to write tests."
        </x-p>

        <x-p class="text-2xl md:text-3xl text-[#64748b] italic">
            But you do have time and money to fix bugs in production?
        </x-p>
    </x-body>
@endsection
