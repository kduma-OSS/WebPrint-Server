@props(['primary' => false, 'multiple' => false, 'href' => null])

@if($href)
    <a href="{{ $href }}"
        {{ $attributes->class([
            'inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
            'ml-3' => $multiple,
            'border-gray-300 bg-white text-gray-700 hover:bg-gray-50' => ! $primary,
            'border-transparent bg-indigo-600 text-white hover:bg-indigo-700' => $primary,
        ]) }}
    >
        {{ $slot }}
    </a>
@else
    <button type="button"
        {{ $attributes->class([
            'inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2',
            'ml-3' => $multiple,
            'border-gray-300 bg-white text-gray-700 hover:bg-gray-50' => ! $primary,
            'border-transparent bg-indigo-600 text-white hover:bg-indigo-700' => $primary,
        ]) }}
    >
        {{ $slot }}
    </button>
@endif
