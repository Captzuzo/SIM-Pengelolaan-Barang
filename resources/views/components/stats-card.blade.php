{{-- <div class="bg-dark rounded-lg shadow p-4 flex items-center justify-between">
    <div class="text-2xl font-bold">
        {{ $value }}
    </div>
    <div>
        {!! $icon !!}
    </div>
</div> --}}
<div class="flex items-center justify-between">
    <span class="text-4xl font-extrabold">{{ $value }}</span>
    <x-dynamic-component :component="$icon" class="w-10 h-10 text-gray-900" />
</div>