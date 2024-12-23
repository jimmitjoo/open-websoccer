<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gradient-to-br from-green-800 to-green-900 text-white">
    <div class="relative min-h-screen">
        <!-- Hero-sektion -->
        <div class="relative pt-24 pb-32 flex flex-col items-center">
            <h1 class="text-5xl font-bold mb-6">{{ config('app.name') }}</h1>
            <p class="text-xl mb-12 text-center max-w-2xl">
                Ta kontrollen över din favoritklubb och led den till ära och framgång i Sveriges mest realistiska
                fotbollsmanagerspel.
            </p>

            @if (Route::has('login'))
                <div class="space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition">
                            Gå till Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition">
                            Logga in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="bg-white hover:bg-gray-100 text-green-800 font-bold py-3 px-6 rounded-lg transition">
                                Registrera dig
                            </a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <!-- Funktioner -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div
                    class="bg-white/10 backdrop-blur-sm p-8 rounded-xl transform hover:scale-105 transition-transform duration-300">
                    <div class="text-green-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Taktisk Djup</h3>
                    <p class="text-gray-300">
                        Utveckla din egen spelstil, sätt upp taktiker och se ditt lag växa under din ledning.
                    </p>
                </div>

                <div
                    class="bg-white/10 backdrop-blur-sm p-8 rounded-xl transform hover:scale-105 transition-transform duration-300">
                    <div class="text-green-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Transfermarknad</h3>
                    <p class="text-gray-300">
                        Köp och sälj spelare, förhandla kontrakt och bygg ditt drömlag på transfermarknaden.
                    </p>
                </div>

                <div
                    class="bg-white/10 backdrop-blur-sm p-8 rounded-xl transform hover:scale-105 transition-transform duration-300">
                    <div class="text-green-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Ungdomsakademi</h3>
                    <p class="text-gray-300">
                        Utveckla morgondagens stjärnor genom din ungdomsakademi och scoutingverksamhet.
                    </p>
                </div>

                <!-- Ligaspel -->
                <div
                    class="bg-white/10 backdrop-blur-sm p-8 rounded-xl transform hover:scale-105 transition-transform duration-300">
                    <div class="text-green-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Ligaspel</h3>
                    <p class="text-gray-300">
                        Tävla i Sveriges ligor, från Allsvenskan till Division 2, och kämpa om titlar och uppflyttning.
                    </p>
                </div>

                <!-- Ekonomi & Klubbledning -->
                <div
                    class="bg-white/10 backdrop-blur-sm p-8 rounded-xl transform hover:scale-105 transition-transform duration-300">
                    <div class="text-green-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Ekonomi & Klubbledning</h3>
                    <p class="text-gray-300">
                        Hantera klubbens ekonomi, utveckla arenan och bygg en hållbar framtid för din förening.
                    </p>
                </div>

                <!-- Träning & Utveckling -->
                <div
                    class="bg-white/10 backdrop-blur-sm p-8 rounded-xl transform hover:scale-105 transition-transform duration-300">
                    <div class="text-green-400 mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Träning & Utveckling</h3>
                    <p class="text-gray-300">
                        Skräddarsy träningsprogram, utveckla spelarnas färdigheter och maximera lagets potential.
                    </p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl">
                    <div class="text-3xl font-bold text-green-400">150+</div>
                    <div class="text-gray-300">Klubbar</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl">
                    <div class="text-3xl font-bold text-green-400">4</div>
                    <div class="text-gray-300">Divisioner</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl">
                    <div class="text-3xl font-bold text-green-400">5000+</div>
                    <div class="text-gray-300">Spelare</div>
                </div>
                <div class="bg-white/5 backdrop-blur-sm p-6 rounded-xl">
                    <div class="text-3xl font-bold text-green-400">1000+</div>
                    <div class="text-gray-300">Managers</div>
                </div>
            </div>
        </div>

        <footer class="absolute bottom-0 w-full py-6 text-center text-sm text-gray-400">
            Football Manager Sverige &copy; {{ date('Y') }} | Version {{ config('app.version', '1.0.0') }}
        </footer>
    </div>
</body>

</html>
