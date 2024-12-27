@props(['disabled' => false, 'size' => 'md'])

@php
    $baseClasses =
        'inline-flex items-center border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150';

    $sizeClasses = match ($size) {
        'sm' => 'px-2 py-1',
        'lg' => 'px-6 py-3',
        default => 'px-4 py-2',
    };

    $classes = $disabled
        ? $baseClasses . ' ' . $sizeClasses . ' opacity-50 cursor-not-allowed bg-gray-300 text-gray-700'
        : $baseClasses .
            ' ' .
            $sizeClasses .
            ' bg-gray-800 dark:bg-gray-200 text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
