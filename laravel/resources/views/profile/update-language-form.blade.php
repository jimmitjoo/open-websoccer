<x-form-section submit="updateLanguage">
    <x-slot name="title">
        {{ __('Språkinställningar') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Välj vilket språk du vill använda i applikationen.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="language" value="{{ __('Språk') }}" />
            <select id="language" wire:model="language"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="en">English</option>
                <option value="sv">Svenska</option>
            </select>
            <x-input-error for="language" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Sparad.') }}
        </x-action-message>

        <x-button>
            {{ __('Spara') }}
        </x-button>
    </x-slot>
</x-form-section>
