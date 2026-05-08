@props(['icon' => null, 'color' => 'blue'])

@php
$colorClasses = match($color) {
    'blue' => 'bg-blue-50 border-blue-200 text-blue-800',
    'green' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
    'amber' => 'bg-amber-50 border-amber-200 text-amber-800',
    'red' => 'bg-red-50 border-red-200 text-red-800',
    'purple' => 'bg-purple-50 border-purple-200 text-purple-800',
    default => 'bg-blue-50 border-blue-200 text-blue-800',
};
@endphp

<div {{ $attributes->merge(['class' => 'rounded-xl border-l-4 p-6 my-6 ' . $colorClasses]) }}>
    @if($icon)
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0 text-3xl">{{ $icon }}</div>
            <div class="flex-1">
                {{ $slot }}
            </div>
        </div>
    @else
        {{ $slot }}
    @endif
</div>
