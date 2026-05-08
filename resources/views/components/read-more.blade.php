@props(['href'])

<x-p>
    Read more: <x-link href="{{ $href }}">{{ $slot }}</x-link>
</x-p>