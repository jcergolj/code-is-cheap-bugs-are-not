@props(['href'])

<x-p>
    Read more by @jcergolj: <x-link href="{{ $href }}">{{ $slot }}</x-link>
</x-p>