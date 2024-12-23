<x-app-layout>
    <x-slot name="header">
        <x-club-navigation :club="$club" :isOwnClub="$isOwnClub" currentPage="overview" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Grundläggande info -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Klubbinformation') }}</h3>
                            <div class="space-y-3">
                                <p>
                                    <span class="font-medium">Liga:</span>
                                    @if ($club->leagues->isNotEmpty())
                                        <a href="{{ route('leagues.show', [$club->leagues->first(), $club->leagues->first()->pivot->season_id]) }}"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                            {{ $club->leagues->first()->name }}
                                        </a>
                                    @else
                                        Ingen liga
                                    @endif
                                </p>
                                @if ($isOwnClub)
                                    <p><span class="font-medium">Budget:</span> {{ number_format($club->budget) }} kr
                                    </p>
                                    <p><span class="font-medium">Inkomster:</span> {{ number_format($club->income) }} kr
                                    </p>
                                    <p><span class="font-medium">Utgifter:</span> {{ number_format($club->expenses) }}
                                        kr</p>
                                @endif
                                <p>
                                    <span class="font-medium">Manager:</span>
                                    @if ($club->user)
                                        {{ $club->user->name }}
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400">Vakant</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <!-- Arena -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ $club->stadium->name }}</h3>
                            <div class="space-y-3">
                                <p><span class="font-medium">Total kapacitet:</span>
                                    {{ number_format(
                                        $club->stadium->capacity_seats + $club->stadium->capacity_stands + $club->stadium->capacity_vip,
                                    ) }}
                                </p>
                                <p><span class="font-medium">Sittplatser:</span>
                                    {{ number_format($club->stadium->capacity_seats) }}</p>
                                <p><span class="font-medium">Ståplatser:</span>
                                    {{ number_format($club->stadium->capacity_stands) }}</p>
                                <p><span class="font-medium">VIP-platser:</span>
                                    {{ number_format($club->stadium->capacity_vip) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik -->
                    @if ($club->leagues->isNotEmpty())
                        <div class="mt-6 bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Säsongsstatistik') }}</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Matcher</p>
                                    <p class="text-2xl font-bold">{{ $club->leagues->first()?->pivot->matches_played }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Poäng</p>
                                    <p class="text-2xl font-bold">{{ $club->leagues->first()?->pivot->points }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Målskillnad</p>
                                    <p class="text-2xl font-bold">
                                        {{ $club->leagues->first()?->pivot->goals_for }} -
                                        {{ $club->leagues->first()?->pivot->goals_against }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Position</p>
                                    <p class="text-2xl font-bold">
                                        {{ $club->leagues->first()?->pivot->current_position ?: '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Senaste 5</p>
                                    <div class="flex gap-1 mt-1">
                                        @foreach (['W', 'D', 'L', 'W', 'W'] as $result)
                                            <span @class([
                                                'w-6 h-6 flex items-center justify-center rounded-full text-xs font-bold',
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100' =>
                                                    $result === 'W',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100' =>
                                                    $result === 'D',
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' =>
                                                    $result === 'L',
                                            ])>
                                                {{ $result }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-6 bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Historiska placeringar') }}</h3>
                        <div class="space-y-2">
                            <p>
                                <span class="font-medium">Bästa placering:</span>
                                {{ $club->leagues->first()?->pivot->highest_position ?: '-' }}
                            </p>
                            <p>
                                <span class="font-medium">Sämsta placering:</span>
                                {{ $club->leagues->first()?->pivot->lowest_position ?: '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-4">
                        <p>
                            <span class="font-medium">Hållna nollor:</span>
                            {{ $club->leagues->first()?->pivot->clean_sheets }}
                        </p>
                        <p>
                            <span class="font-medium">Mållösa matcher:</span>
                            {{ $club->leagues->first()?->pivot->failed_to_score }}
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
