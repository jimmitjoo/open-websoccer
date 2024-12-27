<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New League') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.leagues.store') }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name')" required autofocus />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="country_code" value="{{ __('Country code (ISO)') }}" />
                            <x-input id="country_code" class="block mt-1 w-full" type="text" name="country_code"
                                :value="old('country_code')" required maxlength="2" />
                            @error('country_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="level" value="{{ __('Level') }}" />
                            <select id="level" name="level"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="national" {{ old('level') == 'national' ? 'selected' : '' }}>
                                    {{ __('Nationell') }}</option>
                                <option value="continental" {{ old('level') == 'continental' ? 'selected' : '' }}>
                                    {{ __('Continental') }}</option>
                            </select>
                            @error('level')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="rank" value="{{ __('Rank') }}" />
                            <x-input id="rank" class="block mt-1 w-full" type="number" name="rank"
                                :value="old('rank', 1)" required min="1" />
                            @error('rank')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="max_teams" value="{{ __('Max number of teams') }}" />
                            <x-input id="max_teams" class="block mt-1 w-full" type="number" name="max_teams"
                                :value="old('max_teams', 16)" required min="1" />
                            @error('max_teams')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_relegation" value="1" class="form-checkbox"
                                    {{ old('has_relegation', true) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Has relegation') }}</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_promotion" value="1" class="form-checkbox"
                                    {{ old('has_promotion', true) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Has promotion') }}</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" class="form-checkbox"
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Active') }}</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <x-label for="seasons" value="{{ __('Seasons') }}" />
                            <select name="seasons[]" id="seasons" class="form-multiselect block w-full mt-1" multiple>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->id }}"
                                        {{ in_array($season->id, old('seasons', [])) ? 'selected' : '' }}>
                                        {{ $season->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('seasons')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button class="ml-4">
                                {{ __('Create League') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
