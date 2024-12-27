<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                Ungdomsakademi - Nivå {{ $academy->level->level }}
            </h3>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="stat-card">
                    <div class="stat-label">Ungdomsspelare</div>
                    <div class="stat-value">
                        {{ $academy->youthPlayers()->count() }} / {{ $academy->level->max_youth_players }}
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Nästa spelare tillgänglig</div>
                    <div class="stat-value">
                        {{ $academy->next_youth_player_available_at->diffForHumans() }}
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-label">Total investering</div>
                    <div class="stat-value">{{ number_format($academy->total_investment) }} kr</div>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <x-button wire:click="generatePlayer" :disabled="$academy->youthPlayers()->count() >= $academy->level->max_youth_players">
                    Generera Spelare
                </x-button>

                <x-button wire:click="upgradeAcademy" :disabled="$club->balance < $upgradeCost">
                    Uppgradera ({{ number_format($upgradeCost) }} kr)
                </x-button>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                Ungdomsspelare
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead>
                        <tr>
                            <th>Namn</th>
                            <th>Ålder</th>
                            <th>Position</th>
                            <th>Potential</th>
                            <th>Utveckling</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($youthPlayers as $player)
                            <tr wire:key="player-{{ $player->id }}">
                                <td>{{ $player->first_name }} {{ $player->last_name }}</td>
                                <td>{{ $player->age }}</td>
                                <td>{{ $player->preferred_position }}</td>
                                <td>
                                    <x-rating-badge :value="$player->potential_rating" />
                                </td>
                                <td>
                                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                                        <div class="bg-blue-600 h-2.5 rounded-full"
                                            style="width: {{ $player->development_progress }}%">
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <x-button-link
                                        href="{{ route('youth-academy.player.show', ['club' => $player->youthAcademy->club_id, 'player' => $player]) }}"
                                        size="sm">
                                        Detaljer
                                    </x-button-link>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $youthPlayers->links() }}
            </div>
        </div>
    </div>
</div>
