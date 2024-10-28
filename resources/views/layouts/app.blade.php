<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class=" antialiased">
    <x-banner />

    <div class="min-h-screen ">
        @livewire('navigation-menu')


        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <div class="py-4">



                <div class=" mx-auto w-full">
                    <div x-data="{ menuOpen: false }" class="bg-white overflow-hidden shadow-xl ">
                        <div @click="menuOpen = !menuOpen" class="px-8 cursor-pointer lg:hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                            </svg>

                        </div>
                        <div class="flex lg:grid lg:grid-cols-[200px_minmax(0,1fr)] py-4 relative">

                            <div class="mt-4 transition duration-500 ease-in-out max-lg:min-w-[180px]   lg:block"
                                :class="{ 'block': menuOpen, 'hidden': !menuOpen }">
                                <livewire:sidepanel active="{{ $label ?? '' }}" />

                            </div>
                            <div class="max-lg:flex-1 relative "
                                :class="menuOpen ? ' after:absolute after:inset-0 after:bg-gray-400/[0.9]' : ''">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>

    @livewireScripts
    @stack('scripts')
    @stack('modals')
</body>

</html>
