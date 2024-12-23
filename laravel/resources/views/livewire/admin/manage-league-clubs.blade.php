<div>
    <div class="mb-4">
        <x-label for="season" value="{{ __('Välj säsong') }}" />
        <select wire:model.live="selectedSeason" id="season"
            class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="">{{ __('Välj säsong...') }}</option>
            @foreach ($seasons as $season)
                <option value="{{ $season->id }}">{{ $season->name }}</option>
            @endforeach
        </select>
    </div>

    @if ($selectedSeason)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Tillgängliga lag -->
            <div>
                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Tillgängliga lag') }}
                </h4>
                <div class="space-y-2">
                    @forelse($availableClubs as $club)
                        <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-700 rounded-lg shadow">
                            <span class="text-sm text-gray-900 dark:text-gray-100">{{ $club->name }}</span>
                            <button wire:click="addClub({{ $club->id }})"
                                class="px-3 py-1 text-xs text-white bg-green-500 hover:bg-green-600 rounded">
                                {{ __('Lägg till') }}
                            </button>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Inga tillgängliga lag') }}
                        </p>
                    @endforelse
                </div>
            </div>

            <!-- Lag i ligan -->
            <div>
                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ __('Lag i ligan') }}
                </h4>
                <div class="space-y-2">
                    @forelse($league->clubs()->wherePivot('season_id', $selectedSeason)->get() as $club)
                        <div class="flex items-center justify-between p-2 bg-white dark:bg-gray-700 rounded-lg shadow">
                            <span class="text-sm text-gray-900 dark:text-gray-100">{{ $club->name }}</span>
                            <button wire:click="removeClub({{ $club->id }})"
                                class="px-3 py-1 text-xs text-white bg-red-500 hover:bg-red-600 rounded">
                                {{ __('Ta bort') }}
                            </button>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('Inga lag i ligan') }}
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
