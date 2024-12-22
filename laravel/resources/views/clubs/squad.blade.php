<x-app-layout>
    <x-slot name="header">
        <x-club-navigation :club="$club" :isOwnClub="$isOwnClub" currentPage="squad" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @foreach (['GK', 'DEF', 'MID', 'FWD'] as $position)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold mb-4">
                                @switch($position)
                                    @case('GK')
                                        Målvakter
                                    @break

                                    @case('DEF')
                                        Försvarare
                                    @break

                                    @case('MID')
                                        Mittfältare
                                    @break

                                    @case('FWD')
                                        Anfallare
                                    @break
                                @endswitch
                            </h3>

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2 text-left">Namn</th>
                                            <th class="px-4 py-2 text-left">Ålder</th>
                                            @if ($isOwnClub)
                                                <th class="px-4 py-2 text-left">Form</th>
                                                @foreach (['strength', 'stamina', 'speed', 'technique', 'passing'] as $attribute)
                                                    <th class="px-4 py-2 text-center">
                                                        {{ ucfirst($attribute) }}
                                                    </th>
                                                @endforeach
                                                <th class="px-4 py-2 text-center">
                                                    {{ $position === 'GK' ? 'Målvakt' : ($position === 'DEF' ? 'Försvar' : ($position === 'MID' ? 'Mittfält' : 'Anfall')) }}
                                                </th>
                                                <th class="px-4 py-2 text-right">Lön</th>
                                                <th class="px-4 py-2 text-right">Kontrakt till</th>
                                            @else
                                                <th class="px-4 py-2 text-center">Fysik</th>
                                                <th class="px-4 py-2 text-center">Teknik</th>
                                                <th class="px-4 py-2 text-center">
                                                    {{ $position === 'GK' ? 'Målvakt' : ($position === 'DEF' ? 'Försvar' : ($position === 'MID' ? 'Mittfält' : 'Anfall')) }}
                                                </th>
                                                <th class="px-4 py-2 text-right">Kontrakt till</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($club->players->where('position', $position) as $player)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-4 py-2">
                                                    {{ $player->first_name }} {{ $player->last_name }}
                                                </td>
                                                <td class="px-4 py-2">{{ $player->birth_date->age }}</td>
                                                @if ($isOwnClub)
                                                    <td class="px-4 py-2">{{ $player->form }}</td>
                                                    @foreach (['strength', 'stamina', 'speed', 'technique', 'passing'] as $attribute)
                                                        <td class="px-4 py-2 text-center">{{ $player->$attribute }}</td>
                                                    @endforeach
                                                    <td class="px-4 py-2 text-center">
                                                        {{ $player->{strtolower($position === 'GK' ? 'goalkeeper' : ($position === 'DEF' ? 'defense' : ($position === 'MID' ? 'midfield' : 'striker')))} }}
                                                    </td>
                                                    <td class="px-4 py-2 text-right">
                                                        @if ($player->hasActiveContract())
                                                            {{ number_format($player->activeContract->salary) }} kr
                                                            <div class="mt-1 flex gap-2 justify-end">
                                                                <button
                                                                    onclick="negotiateContract({{ $player->id }})"
                                                                    class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                                                    Förhandla
                                                                </button>
                                                                <button
                                                                    onclick="terminateContract({{ $player->activeContract->id }})"
                                                                    class="text-xs bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                                                    Bryt kontrakt
                                                                </button>
                                                            </div>
                                                        @else
                                                            <span class="text-red-600 dark:text-red-400">Inget aktivt
                                                                kontrakt</span>
                                                            <div class="mt-1 flex gap-2 justify-end">
                                                                <button
                                                                    onclick="negotiateContract({{ $player->id }})"
                                                                    class="text-xs bg-blue-500 text-white px-2 py-1 rounded hover:bg-blue-600">
                                                                    Förhandla
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-4 py-2 text-right">
                                                        @if ($player->hasActiveContract())
                                                            {{ $player->activeContract->end_date->format('Y-m-d') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                @else
                                                    <td class="px-4 py-2 text-center">
                                                        @php
                                                            $physicalAvg = round(
                                                                ($player->strength +
                                                                    $player->stamina +
                                                                    $player->speed) /
                                                                    3,
                                                            );
                                                        @endphp
                                                        <span @class([
                                                            'px-2 py-1 rounded text-sm',
                                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' =>
                                                                $physicalAvg < 45,
                                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100' =>
                                                                $physicalAvg >= 45 && $physicalAvg < 65,
                                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100' =>
                                                                $physicalAvg >= 65,
                                                        ])>
                                                            {{ $physicalAvg < 45 ? 'Svag' : ($physicalAvg < 65 ? 'Medel' : 'Stark') }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        @php
                                                            $technicalAvg = round(
                                                                ($player->technique + $player->passing) / 2,
                                                            );
                                                        @endphp
                                                        <span @class([
                                                            'px-2 py-1 rounded text-sm',
                                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' =>
                                                                $technicalAvg < 45,
                                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100' =>
                                                                $technicalAvg >= 45 && $technicalAvg < 65,
                                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100' =>
                                                                $technicalAvg >= 65,
                                                        ])>
                                                            {{ $technicalAvg < 45 ? 'Svag' : ($technicalAvg < 65 ? 'Medel' : 'Skicklig') }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-center">
                                                        @php
                                                            $positionSkill =
                                                                $player->{strtolower(
                                                                    $position === 'GK'
                                                                        ? 'goalkeeper'
                                                                        : ($position === 'DEF'
                                                                            ? 'defense'
                                                                            : ($position === 'MID'
                                                                                ? 'midfield'
                                                                                : 'striker')),
                                                                )};
                                                        @endphp
                                                        <span @class([
                                                            'px-2 py-1 rounded text-sm',
                                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100' =>
                                                                $positionSkill < 45,
                                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100' =>
                                                                $positionSkill >= 45 && $positionSkill < 65,
                                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100' =>
                                                                $positionSkill >= 65,
                                                        ])>
                                                            {{ $positionSkill < 45 ? 'Svag' : ($positionSkill < 65 ? 'Medel' : 'Utmärkt') }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-right">
                                                        @if ($player->hasActiveContract())
                                                            {{ $player->activeContract->end_date->format('Y-m-d') }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal för kontraktsförhandling -->
    <div id="negotiateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Förhandla nytt kontrakt</h3>
                <div class="mt-2">
                    <form id="negotiateForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Lön per
                                månad</label>
                            <input type="number" name="salary" id="salary" min="1000"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kontraktslängd
                                (månader)</label>
                            <input type="number" name="duration" id="duration" min="1" max="60"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeNegotiateModal()"
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Avbryt
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Förhandla
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentPlayerId = null;
            const modal = document.getElementById('negotiateModal');
            const form = document.getElementById('negotiateForm');

            function negotiateContract(playerId) {
                currentPlayerId = playerId;
                modal.classList.remove('hidden');
            }

            function closeNegotiateModal() {
                modal.classList.add('hidden');
                form.reset();
            }

            function terminateContract(contractId) {
                if (confirm('Är du säker på att du vill bryta kontraktet? Detta kommer kosta klubben pengar.')) {
                    fetch(`/contracts/${contractId}/terminate`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            } else {
                                alert(data.message || 'Ett fel uppstod');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Ett fel uppstod');
                        });
                }
            }

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = {
                    salary: parseInt(document.getElementById('salary').value),
                    duration: parseInt(document.getElementById('duration').value)
                };

                fetch(`/players/${currentPlayerId}/negotiate`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (!response.ok) {
                            closeNegotiateModal();
                            throw new Error(data.message);
                        }
                        return data;
                    })
                    .then(data => {
                        if (data.success && data.accepted) {
                            alert(data.message);
                            window.location.reload();
                        } else {
                            alert(data.message);
                            closeNegotiateModal();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert(error.message);
                    });
            });

            // Stäng modal om man klickar utanför
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeNegotiateModal();
                }
            });
        </script>
    @endpush
</x-app-layout>
