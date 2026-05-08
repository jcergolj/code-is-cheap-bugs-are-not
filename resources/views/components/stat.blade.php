@props(['label', 'value', 'icon' => null, 'color' => 'blue'])

@php
$colorClasses = match($color) {
    'blue' => 'bg-blue-500',
    'green' => 'bg-emerald-500',
    'amber' => 'bg-amber-500',
    'red' => 'bg-red-500',
    'purple' => 'bg-purple-500',
    default => 'bg-blue-500',
};
@endphp

<div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 text-center hover:shadow-lg transition-all duration-200">
    @if($icon)
        <div class="text-4xl mb-3">{{ $icon }}</div>
    @endif
    <div class="text-5xl font-bold {{ $colorClasses }} bg-clip-text text-transparent mb-2">
        {{ $value }}
    </div>
    <div class="text-gray-600 font-medium text-lg">{{ $label }}</div>
</div>
