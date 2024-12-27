<x-app-layout>
    <x-slot name="header">
        <x-club-navigation :club="$club" :isOwnClub="true" currentPage="clubhouse" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Klubbinfo -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Grundläggande info -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Club information') }}</h3>
                            <div class="space-y-3">
                                <p>
                                    <span class="font-medium">{{ __('League') }}:</span>
                                    @if ($club->leagues->isNotEmpty())
                                        <a href="{{ route('leagues.show', [$club->leagues->first(), $club->leagues->first()->pivot->season_id]) }}"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                                            {{ $club->leagues->first()->name }}
                                        </a>
                                    @else
                                        {{ __('No league') }}
                                    @endif
                                </p>
                                <p><span class="font-medium">{{ __('Budget') }}:</span>
                                    {{ number_format($club->budget) }} kr</p>
                                <p><span class="font-medium">{{ __('Income') }}:</span>
                                    {{ number_format($club->income) }} kr</p>
                                <p><span class="font-medium">{{ __('Expenses') }}:</span>
                                    {{ number_format($club->expenses) }} kr
                                </p>
                            </div>
                        </div>

                        <!-- Arena -->
                        <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ $club->stadium->name }}</h3>
                            <div class="space-y-3">
                                <p><span class="font-medium">{{ __('Total capacity') }}:</span>
                                    {{ number_format(
                                        $club->stadium->capacity_seats + $club->stadium->capacity_stands + $club->stadium->capacity_vip,
                                    ) }}
                                </p>
                                <p><span class="font-medium">{{ __('Seats') }}:</span>
                                    {{ number_format($club->stadium->capacity_seats) }}</p>
                                <p><span class="font-medium">{{ __('Stands') }}:</span>
                                    {{ number_format($club->stadium->capacity_stands) }}</p>
                                <p><span class="font-medium">{{ __('VIP-seats') }}:</span>
                                    {{ number_format($club->stadium->capacity_vip) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik -->
                    @if ($club->leagues->isNotEmpty())
                        <div class="mt-6 bg-gray-50 dark:bg-gray-900 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">{{ __('Season statistics') }}</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Matches') }}</p>
                                    <p class="text-2xl font-bold">{{ $club->leagues->first()->pivot->matches_played }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Points') }}</p>
                                    <p class="text-2xl font-bold">{{ $club->leagues->first()->pivot->points }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Goal difference') }}</p>
                                    <p class="text-2xl font-bold">
                                        {{ $club->leagues->first()->pivot->goals_for }} -
                                        {{ $club->leagues->first()->pivot->goals_against }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Position') }}</p>
                                    <p class="text-2xl font-bold">
                                        {{ $club->leagues->first()->pivot->current_position ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
