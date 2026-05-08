@props(['items' => []])

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 my-8">
    @foreach($items as $item)
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6 hover:shadow-lg transition-shadow duration-200">
            @if(isset($item['icon']))
                <div class="text-4xl mb-4">{{ $item['icon'] }}</div>
            @endif
            @if(isset($item['title']))
                <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item['title'] }}</h3>
            @endif
            @if(isset($item['description']))
                <p class="text-gray-600">{{ $item['description'] }}</p>
            @endif
        </div>
    @endforeach
    {{ $slot }}
</div>
