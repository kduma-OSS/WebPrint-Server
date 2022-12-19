@props(['buttons' => null])

<div {{ $attributes->class(['md:flex md:items-center md:justify-between']) }}>
    <div class="min-w-0 flex-1">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight sm:truncate sm:tracking-tight">
            {{ $slot }}
        </h2>
    </div>
    @if($buttons)
        <div {{ $buttons->attributes->class(['mt-4 flex md:mt-0 md:ml-4']) }}>
            {{ $buttons }}
        </div>
    @endif
</div>
