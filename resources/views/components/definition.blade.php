@props(['term', 'definition'])

<div class="bg-white rounded-lg border border-gray-200 p-5 my-4 hover:border-amber-300 hover:shadow-md transition-all duration-200">
    <dt class="text-xl font-bold text-amber-700 mb-2">{{ $term }}</dt>
    <dd class="text-lg text-gray-600 leading-relaxed">{{ $definition }}</dd>
</div>
