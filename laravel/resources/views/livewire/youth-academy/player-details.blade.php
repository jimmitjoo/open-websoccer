<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        {{ $player->first_name }} {{ $player->last_name }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $player->age }} år | {{ $player->preferred_position }} | {{ $player->nationality }}
                    </p>
                </div>

                <div class="flex items-center space-x-4">
                    <x-rating-badge :value="$player->potential_rating" label="Potential" />
                    <x-rating-badge :value="$player->current_ability" label="Nuvarande" />
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-4">Player Attributes</h4>
                    <div class="space-y-3">
                        @foreach ($playerAttributes as $key => $label)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</span>
                                <div class="flex items-center space-x-3">
                                    <x-rating-badge size="sm" :value="$player->$key" />
                                    <x-button size="sm" wire:click="train('{{ $key }}')"
                                        wire:loading.attr="disabled" :disabled="$player->last_training_at?->isToday()">
                                        Träna
                                    </x-button>
                                </div>
                            </div>
                        @endforeach
                        @if ($player->last_training_at?->isToday())
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
                                Nästa träning tillgänglig {{ $player->last_training_at->addDay()->diffForHumans() }}
                            </p>
                        @endif
                    </div>
                </div>

                <div>
                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-4">Personality</h4>
                    <div class="space-y-3">
                        @foreach ($personalityAttributes as $key => $label)
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</span>
                                <x-rating-badge size="sm" :value="$player->$key" />
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Development</h4>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-blue-600 h-2.5 rounded-full"
                                style="width: {{ $player->development_progress }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <x-button wire:click="promotePlayer" :disabled="!$player->promotion_available_at || $player->promotion_available_at->isFuture()">
                    Flytta upp till A-laget
                </x-button>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100 mb-4">
                Utvecklingshistorik
            </h3>

            <div class="space-y-4">
                @foreach ($developmentLogs as $log)
                    <div class="flex items-center justify-between py-2 border-b border-gray-200 dark:border-gray-700">
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $playerAttributes[$log->attribute_name] ?? ($personalityAttributes[$log->attribute_name] ?? $log->attribute_name) }}
                            </span>
                            <div class="text-sm">
                                {{ $log->note }}
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <x-rating-badge size="sm" :value="$log->old_value" />
                            <span class="text-gray-500 dark:text-gray-400">→</span>
                            <x-rating-badge size="sm" :value="$log->new_value" />
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                {{ $developmentLogs->links() }}
            </div>
        </div>
    </div>
</div>
