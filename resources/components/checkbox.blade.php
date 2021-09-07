<label class="inline-flex font-normal">
    <div class="w-5 h-5 bg-white m-1 border-2 flex-shrink-0 flex items-center justify-center rounded border-{{ $color }}-500">
        <input
            type="checkbox"
            ref="input"
            class="absolute opacity-0"
            {{ $attributes }}
            {{ $checked ? 'checked' : '' }}
            onchange="
                const indicator = event.target.parentNode.querySelector('#indicator');

                if (event.target.checked) {
                    indicator.classList.add('bg-{{ $color }}-500', 'shadow');
                }
                else {
                    indicator.classList.remove('bg-{{ $color }}-500', 'shadow');
                }
            "
        >

        <div class="w-3 h-3 {{ $checked ? 'bg-' . $color . '-500 shadow' : '' }}" id="indicator"></div>
    </div>

    @if ($slot)
        <div class="text-sm ml-1">
            <div class="flex items-center h-full">
                {{ $slot }}
            </div>
        </div>
    @endif
</label>