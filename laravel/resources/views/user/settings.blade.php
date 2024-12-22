<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inställningar') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form method="POST" action="{{ route('user.settings.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <x-label for="locale" value="{{ __('Språk') }}" />
                        <select name="locale" id="locale"
                            class="block mt-1 w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                            <option value="en" {{ auth()->user()->locale === 'en' ? 'selected' : '' }}>English
                            </option>
                            <option value="sv" {{ auth()->user()->locale === 'sv' ? 'selected' : '' }}>Svenska
                            </option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <x-checkbox name="settings[email_notifications]" value="1" :checked="auth()->user()->settings['email_notifications'] ?? false" />
                            <span class="ml-2">{{ __('Få e-postmeddelanden') }}</span>
                        </label>
                    </div>

                    <x-button>
                        {{ __('Spara') }}
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
