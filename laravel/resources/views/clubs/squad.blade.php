<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $club->name }} - Trupp
            </h2>
        </div>
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
                                                        {{ number_format($player->activeContract->salary) }} kr
                                                    </td>
                                                    <td class="px-4 py-2 text-right">
                                                        {{ $player->activeContract->end_date->format('Y-m-d') }}
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
                                                        {{ $player->activeContract->end_date->format('Y-m-d') }}
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
</x-app-layout>
