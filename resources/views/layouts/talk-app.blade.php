<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if ($viewTransitions ?? false)
        <meta name="view-transition" content="same-origin" />
        @endif
        {{ $meta ?? '' }}

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <link rel="stylesheet" href="{{ tailwindcss('css/app.css') }}">

        <!-- Scripts -->
        <x-importmap::tags />
    </head>
    <body class="antialiased gradient-bg text-[#0f172a] h-screen overflow-hidden flex flex-col" style="font-family: 'Inter', sans-serif; font-size: 18px; line-height: 1.6;">
        <x-progress-bar />

        <main class="flex-1 flex flex-col justify-start selection:bg-[#0284c7] selection:text-white overflow-hidden min-h-0">
            @hasSection('content-center')
                <section class="flex-1 px-4 lg:px-10 flex flex-col justify-center items-center overflow-hidden min-h-0">
                    @yield('content-center')
                </section>
            @else
                <section class="flex-1 px-4 lg:px-10 pt-12 overflow-auto min-h-0">
                    @yield('content')
                </section>
            @endif
        </main>

        <div class="px-4 pb-4 lg:px-10 w-full flex-shrink-0">
            <x-footer :next="$next ?? null" :previous="$previous ?? null" />
        </div>

        @yield('scripts')

        <script>
            document.addEventListener('keydown', function(e) {
                const scrollable = document.querySelector('section.overflow-auto');
                if (!scrollable) return;

                const scrollAmount = 100;

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    scrollable.scrollBy({ top: scrollAmount, behavior: 'smooth' });
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    scrollable.scrollBy({ top: -scrollAmount, behavior: 'smooth' });
                }
            });
        </script>
    </body>
</html>
