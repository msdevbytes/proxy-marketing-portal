<div class="dark:bg-gray-800 overflow-hidden bg-gray-300">
    <x-filament::input.wrapper class="fi-fo-select" :native="false" :attributes="\Filament\Support\prepare_inherited_attributes($this->getExtraAttributeBag())">
        <x-filament::input.select wire:model="selectedOption" wire:change="optionSelected">
            <option value="">
                Selected
            </option>

            @foreach ($options as $id => $option)
                <option value="{{ $id }}">{{ $option }}</option>
            @endforeach
        </x-filament::input.select>
    </x-filament::input.wrapper>
</div>
