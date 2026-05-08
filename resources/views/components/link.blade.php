@props(['href'])

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'underline text-[#0284c7] hover:text-[#0369a1] hover:bg-sky-50 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#38bdf8] transition-colors duration-200 px-1 py-0.5']) }}>{{ $slot }}</a>
