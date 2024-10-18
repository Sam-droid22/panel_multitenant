@php
    $chartId = $this->getChartId();
    $color = $this->getColor();
    $heading = $this->getHeading();
    $description = $this->getDescription();
    $filters = $this->getFilters();
@endphp

<x-filament-widgets::widget>
    <x-filament::section
        :description="$description"
        :heading="$heading"
        class="fi-wi-chart"
    >
        @if ($filters)
            <x-slot name="headerEnd">
                <x-filament::input.wrapper
                    inline-prefix
                    wire:target="filter"
                    class="-my-2"
                >
                    <x-filament::input.select
                        inline-prefix
                        wire:model.live="filter"
                    >
                        @foreach ($filters as $value => $label)
                            <option value="{{ $value }}">
                                {{ $label }}
                            </option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </x-slot>
        @endif

        <div
            @if ($pollingInterval = $this->getPollingInterval())
                wire:poll.{{ $pollingInterval }}="updateChartSeries"
            @endif
        >
            <div
                ax-load
                ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('glow-chart', 'hasnayeen/glow-chart') }}"
                wire:ignore
                x-data="glowChart({
                    id: @js($chartId),
                    options: @js($this->getChartOptions()),
                })"
                x-ignore
                @style([
                    \Filament\Support\get_color_css_variables($color, shades: [50, 400, 500]) => $color !== 'gray',
                ])
            >
                <div
                    x-ref="{{ $chartId }}"
                    @if ($maxHeight = $this->getMaxHeight())
                        style="max-height: {{ $maxHeight }}"
                    @endif
                ></div>

                <span
                    x-ref="backgroundColorElement"
                    @class([
                        match ($color) {
                            'gray' => 'text-gray-100 dark:text-gray-800',
                            default => 'text-custom-50 dark:text-custom-400/10',
                        },
                    ])
                ></span>

                <span
                    x-ref="borderColorElement"
                    @class([
                        match ($color) {
                            'gray' => 'text-gray-400',
                            default => 'text-custom-500 dark:text-custom-400',
                        },
                    ])
                ></span>

                <span
                    x-ref="gridColorElement"
                    class="text-gray-200 dark:text-gray-800"
                ></span>

                <span
                    x-ref="textColorElement"
                    class="text-gray-500 dark:text-gray-400"
                ></span>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
