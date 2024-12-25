<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Träning') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- Schemalägg nytt träningspass -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Schemalägg träning') }}
                    </h3>

                    <form action="{{ route('training.schedule') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-label for="training_type_id" value="{{ __('Träningstyp') }}" />
                                <select id="training_type_id" name="training_type_id"
                                    class="mt-1 block w-full dark:bg-gray-700">
                                    @foreach ($trainingTypes as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-label for="date" value="{{ __('Datum') }}" />
                                <x-input type="date" id="date" name="date" class="mt-1 block w-full"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required />
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-label value="{{ __('Välj spelare') }}" class="mb-2" />
                            <div class="mb-4">
                                <label class="flex items-center">
                                    <input type="checkbox" id="select-all"
                                        class="rounded dark:bg-gray-700 border-gray-300 dark:border-gray-600">
                                    <span class="ml-2 text-sm">{{ __('Markera/avmarkera alla') }}</span>
                                </label>
                            </div>

                            <script>
                                document.getElementById('select-all').addEventListener('change', function() {
                                    const checkboxes = document.querySelectorAll('input[name="player_ids[]"]:not(:disabled)');
                                    checkboxes.forEach(checkbox => {
                                        checkbox.checked = this.checked;
                                    });
                                });
                            </script>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach (auth()->user()->club->players as $player)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="player_ids[]" value="{{ $player->id }}"
                                            class="rounded dark:bg-gray-700 border-gray-300 dark:border-gray-600"
                                            @disabled($player->is_injured)>
                                        <span class="ml-2 text-sm {{ $player->is_injured ? 'text-red-500' : '' }}">
                                            {{ $player->first_name }} {{ $player->last_name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6">
                            <x-button>
                                {{ __('Schemalägg träning') }}
                            </x-button>
                        </div>
                    </form>
                </div>

                <!-- Lista schemalagda träningar -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                        {{ __('Schemalagda träningar') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">
                                        {{ __('Datum') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">
                                        {{ __('Typ') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">
                                        {{ __('Spelare') }}
                                    </th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left">
                                        {{ __('Status') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($sessions as $session)
                                    <tr>
                                        <td class="px-6 py-4">
                                            {{ $session->scheduled_date->format('Y-m-d') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $session->trainingType->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $session->players->count() }} spelare
                                        </td>
                                        <td class="px-6 py-4">
                                            <span @class([
                                                'px-2 py-1 text-xs rounded-full',
                                                'bg-yellow-100 text-yellow-800' => $session->status === 'scheduled',
                                                'bg-green-100 text-green-800' => $session->status === 'completed',
                                                'bg-red-100 text-red-800' => $session->status === 'cancelled',
                                            ])>
                                                {{ __($session->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4"
                                            class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            {{ __('Inga träningar schemalagda') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $sessions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
