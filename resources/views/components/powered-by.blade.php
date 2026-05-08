@props(['href'])

<x-p>
    Powered by @jcergolj <x-link href="{{ $href }}">{{ $slot }}</x-link>
</x-p>