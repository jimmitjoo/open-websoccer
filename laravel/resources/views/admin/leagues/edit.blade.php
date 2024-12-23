<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Redigera Liga') }}: {{ $league->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('admin.leagues.update', $league) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <x-label for="name" value="{{ __('Namn') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name"
                                :value="old('name', $league->name)" required autofocus />
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="country_code" value="{{ __('Landskod (ISO)') }}" />
                            <x-input id="country_code" class="block mt-1 w-full" type="text" name="country_code"
                                :value="old('country_code', $league->country_code)" required maxlength="2" />
                            @error('country_code')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="level" value="{{ __('Nivå') }}" />
                            <select id="level" name="level"
                                class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="national"
                                    {{ old('level', $league->level) == 'national' ? 'selected' : '' }}>
                                    {{ __('Nationell') }}</option>
                                <option value="continental"
                                    {{ old('level', $league->level) == 'continental' ? 'selected' : '' }}>
                                    {{ __('Kontinental') }}</option>
                            </select>
                            @error('level')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="rank" value="{{ __('Rang') }}" />
                            <x-input id="rank" class="block mt-1 w-full" type="number" name="rank"
                                :value="old('rank', $league->rank)" required min="1" />
                            @error('rank')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <x-label for="max_teams" value="{{ __('Max antal lag') }}" />
                            <x-input id="max_teams" class="block mt-1 w-full" type="number" name="max_teams"
                                :value="old('max_teams', $league->max_teams)" required min="1" />
                            @error('max_teams')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_relegation" value="1" class="form-checkbox"
                                    {{ old('has_relegation', $league->has_relegation) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Har nedflyttning') }}</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="has_promotion" value="1" class="form-checkbox"
                                    {{ old('has_promotion', $league->has_promotion) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Har uppflyttning') }}</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" class="form-checkbox"
                                    {{ old('is_active', $league->is_active) ? 'checked' : '' }}>
                                <span class="ml-2">{{ __('Aktiv') }}</span>
                            </label>
                        </div>

                        <div class="mb-4">
                            <x-label for="seasons" value="{{ __('Säsonger') }}" />
                            <select name="seasons[]" id="seasons" class="form-multiselect block w-full mt-1" multiple>
                                @foreach ($seasons as $season)
                                    <option value="{{ $season->id }}"
                                        {{ in_array($season->id, old('seasons', $league->seasons->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $season->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('seasons')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.leagues.index') }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mr-4">
                                {{ __('Avbryt') }}
                            </a>
                            <x-button>
                                {{ __('Uppdatera Liga') }}
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
