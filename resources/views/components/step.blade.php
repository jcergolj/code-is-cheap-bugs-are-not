@props(['number', 'title' => null])

<div class="flex items-start gap-4 my-6">
    <div class="flex-shrink-0 w-14 h-14 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-2xl font-bold shadow-md">
        {{ $number }}
    </div>
    <div class="flex-1 pt-1">
        @if($title)
            <h3 class="text-2xl font-bold text-gray-800 mb-2">{{ $title }}</h3>
        @endif
        {{ $slot }}
    </div>
</div>
