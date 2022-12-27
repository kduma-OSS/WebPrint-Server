@props([
    'grid' => 3,
])

<ul role="list" {{ $attributes->class([
    'grid gap-6',
    'grid-cols-1 sm:grid-cols-2 lg:grid-cols-2' => $grid == 2,
    'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3' => $grid == 3,
    'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4' => $grid == 4,
]) }}>
    {{ $slot }}
</ul>
