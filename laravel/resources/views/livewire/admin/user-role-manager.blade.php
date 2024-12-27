<div class="p-4 sm:p-6 lg:p-8 bg-white dark:bg-gray-800 shadow-sm rounded-lg">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                {{ __('User roles') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Manage user roles and permissions') }}
            </p>
        </div>
    </div>

    <!-- Search field -->
    <div class="mt-6 max-w-xl">
        <x-input type="search" wire:model.live.debounce.300ms="search" class="block w-full"
            placeholder="{{ __('Search users...') }}" />
    </div>

    <!-- User list -->
    <div class="mt-6 space-y-4">
        @foreach ($users as $user)
            <div class="bg-white dark:bg-gray-900 shadow-sm ring-1 ring-gray-300 dark:ring-gray-700 rounded-lg">
                <div class="px-4 py-4 sm:px-6">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <span
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-500">
                                    <span class="text-sm font-medium leading-none text-white">
                                        {{ substr($user->name, 0, 2) }}
                                    </span>
                                </span>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $user->name }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div>
                                <span @class([
                                    'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset',
                                    'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-400/20' =>
                                        $user->role === 'admin',
                                    'bg-yellow-50 text-yellow-700 ring-yellow-600/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:ring-yellow-400/20' =>
                                        $user->role === 'manager',
                                    'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-400/20' =>
                                        $user->role === 'user',
                                ])>
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            <div class="w-44">
                                <select wire:change="updateRole({{ $user->id }}, $event.target.value)"
                                    class="block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 dark:text-gray-100 dark:bg-gray-800 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}"
                                            {{ $user->role === $role ? 'selected' : '' }}>
                                            {{ __('Change to') }}: {{ ucfirst($role) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Paginering -->
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Notification -->
    <div x-data="{ show: false, message: '' }" x-on:role-updated="show = true; message = 'User role has been updated'" x-show="show"
        x-transition.opacity.duration.500ms x-init="setTimeout(() => show = false, 2000)"
        class="fixed bottom-0 right-0 m-6 p-4 rounded-lg bg-green-500 text-white shadow-lg dark:bg-green-600"
        style="display: none;">
        <p x-text="message" class="text-sm font-medium"></p>
    </div>
</div>
