<x-app-layout>
    <x-slot name="header">
        <x-club-navigation :club="$club" :isOwnClub="$isOwnClub" currentPage="matches" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Season selector -->
                    <div class="mb-6">
                        <label for="season"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Season') }}</label>
                        <select id="season" name="season"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                            @foreach ($seasons as $s)
                                <option value="{{ $s->id }}" {{ $s->id === $season->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Played matches -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4">{{ __('Played matches') }}</h3>
                        <div class="space-y-4">
                            @forelse($playedMatches as $match)
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $match->scheduled_at->format('Y-m-d H:i') }}
                                            </span>
                                            <div class="mt-1 flex justify-between items-center">
                                                <span
                                                    class="font-medium {{ $match->home_club_id === $club->id ? 'font-bold' : '' }}">
                                                    {{ $match->homeClub->name }}
                                                </span>
                                                <span class="mx-4 font-bold">
                                                    {{ $match->home_score }} - {{ $match->away_score }}
                                                </span>
                                                <span
                                                    class="font-medium {{ $match->away_club_id === $club->id ? 'font-bold' : '' }}">
                                                    {{ $match->awayClub->name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">{{ __('No played matches this season') }}
                                </p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Upcoming matches -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">{{ __('Upcoming matches') }}</h3>
                        <div class="space-y-4">
                            @forelse($upcomingMatches as $match)
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                    <div class="flex justify-between items-center">
                                        <div class="flex-1">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $match->scheduled_at->format('Y-m-d H:i') }}
                                            </span>
                                            <div class="mt-1 flex justify-between items-center">
                                                <span
                                                    class="font-medium {{ $match->home_club_id === $club->id ? 'font-bold' : '' }}">
                                                    {{ $match->homeClub->name }}
                                                </span>
                                                <span class="mx-4 text-sm text-gray-500 dark:text-gray-400">vs</span>
                                                <span
                                                    class="font-medium {{ $match->away_club_id === $club->id ? 'font-bold' : '' }}">
                                                    {{ $match->awayClub->name }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 dark:text-gray-400">{{ __('No upcoming matches this season') }}
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('season').addEventListener('change', function() {
                window.location.href = "{{ route('clubs.matches', ['club' => $club->id]) }}?season=" + this.value;
            });
        </script>
    @endpush
</x-app-layout>
