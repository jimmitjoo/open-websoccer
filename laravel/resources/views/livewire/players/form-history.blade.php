<div>
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">{{ __('Form history') }}</h2>

        <div class="space-y-4">
            @foreach ($formHistory as $update)
                <div class="border-b pb-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="font-medium">{{ $update['old_value'] }} → {{ $update['new_value'] }}</span>
                            @if ($update['reason'])
                                <p class="text-sm text-gray-600">{{ $update['reason'] }}</p>
                            @endif
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($update['created_at'])->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
