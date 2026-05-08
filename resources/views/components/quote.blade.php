@props(['quote', 'author' => null, 'role' => null])

<blockquote class="relative my-8 pl-8 border-l-4 border-amber-400 bg-gray-50 rounded-r-xl py-6 pr-6">
    <div class="text-6xl text-amber-300 absolute -top-2 left-4 font-serif">"</div>
    <p class="text-2xl md:text-3xl font-medium text-gray-700 italic relative z-10 leading-relaxed">
        {{ $quote }}
    </p>
    @if($author)
        <footer class="mt-4 text-lg text-gray-500">
            — {{ $author }}
            @if($role)
                <span class="text-gray-400">, {{ $role }}</span>
            @endif
        </footer>
    @endif
</blockquote>
