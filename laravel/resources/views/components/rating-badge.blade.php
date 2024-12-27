@props(['value', 'label' => null, 'color', 'textSize'])

<div {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>
    @if ($label)
        <span class="mr-2 text-gray-500 dark:text-gray-400 {{ $textSize }}">{{ $label }}</span>
    @endif

    <span @class([
        'px-2.5 py-0.5 rounded-full font-medium',
        $textSize,
        "bg-{$color}-100 text-{$color}-800" => !str_contains(
            request()->url(),
            '/admin'),
        "bg-{$color}-100 dark:bg-{$color}-900 text-{$color}-800 dark:text-{$color}-200" => str_contains(
            request()->url(),
            '/admin'),
    ])>
        {{ $value }}
    </span>
</div>
