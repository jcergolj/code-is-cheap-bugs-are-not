@props(['previous' => null, 'next' => null])

<footer class="flex-shrink-0 px-4 py-3 lg:px-10 lg:py-4 bg-[#e0f2fe]/70 backdrop-blur-sm border-t border-[#bae6fd]/50 rounded-lg w-full">
    <div class="flex justify-between items-center w-full">
        @if ($previous !== null)
            <a
                tabindex="2"
                id="previous-button"
                class="px-5 py-2.5 rounded-xl bg-white text-[#0c4a6e] hover:bg-[#e0f2fe] transition-colors text-xl font-bold shadow-lg shadow-white/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0284c7]"
                href="{{ $previous }}"
            >
                ← Previous
            </a>
        @else
            <div class="w-[140px]"></div>
        @endif

        @if ($next !== null)
            <a
                tabindex="1"
                id="next-button"
                class="px-5 py-2.5 rounded-xl bg-[#0284c7] text-[#f1f5f9] hover:bg-[#0369a1] transition-colors text-xl font-bold shadow-lg shadow-[#0284c7]/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0284c7]"
                href="{{ $next }}"
            >
                Next →
            </a>
        @else
            <div class="w-[140px]"></div>
        @endif
    </div>
</footer>