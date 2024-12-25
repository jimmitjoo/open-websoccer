<div class="mt-4">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium">Form: {{ $player->form }}</h3>

        <div class="space-x-2">
            <a href="{{ route('players.form-history', $player) }}" class="text-sm text-blue-600 hover:text-blue-800">
                Visa formhistorik
            </a>

            @can('adjust-form')
                <a href="{{ route('admin.players.adjust-form', $player) }}"
                    class="text-sm text-indigo-600 hover:text-indigo-800">
                    Justera form
                </a>
            @endcan
        </div>
    </div>
</div>
