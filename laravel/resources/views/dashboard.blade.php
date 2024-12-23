<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Översikt') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (!auth()->user()->club)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h2 class="text-xl font-semibold mb-4">
                            {{ __('Välkommen till :appname!', ['appname' => config('app.name')]) }}</h2>
                        <p class="mb-4">
                            {{ __('Du har ännu inte valt någon klubb att leda. Börja din managerkarriär genom att välja en klubb.') }}
                        </p>
                        <a href="{{ route('choose-club') }}"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Välj klubb') }}
                        </a>
                    </div>
                </div>
            @else
                <!-- Klubböversikt -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-xl font-semibold">{{ auth()->user()->club->name }}</h2>
                            <a href="{{ route('clubhouse') }}"
                                class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                {{ __('Gå till klubbhuset') }} →
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Ekonomi -->
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <h3 class="font-medium mb-2">{{ __('Ekonomi') }}</h3>
                                <p class="text-sm mb-1">
                                    <span class="font-medium">{{ __('Budget') }}:</span>
                                    {{ number_format(auth()->user()->club->budget) }} kr
                                </p>
                                <p class="text-sm">
                                    <a href="{{ route('club.finance') }}"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 text-sm">
                                        {{ __('Se ekonomiöversikt') }}
                                    </a>
                                </p>
                            </div>

                            <!-- Liga -->
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <h3 class="font-medium mb-2">{{ __('Liga') }}</h3>
                                @if (auth()->user()->club->leagues->isNotEmpty())
                                    <p class="text-sm mb-1">
                                        <span class="font-medium">{{ __('Position') }}:</span>
                                        {{ auth()->user()->club->leagues->first()->pivot->current_position ?: '-' }}
                                    </p>
                                    <p class="text-sm">
                                        <a href="{{ route('leagues.show', [auth()->user()->club->leagues->first(), auth()->user()->club->leagues->first()->pivot->season_id]) }}"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                            {{ __('Se ligatabell') }}
                                        </a>
                                    </p>
                                @else
                                    <p class="text-sm text-gray-500">{{ __('Ingen liga tilldelad') }}</p>
                                @endif
                            </div>

                            <!-- Trupp -->
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <h3 class="font-medium mb-2">{{ __('Trupp') }}</h3>
                                <p class="text-sm mb-1">
                                    <a href="{{ route('clubs.squad', auth()->user()->club) }}"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                        {{ __('Hantera truppen') }}
                                    </a>
                                </p>
                                <p class="text-sm">
                                    <a href="{{ route('free-agents.index') }}"
                                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                        {{ __('Sök free agents') }}
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
