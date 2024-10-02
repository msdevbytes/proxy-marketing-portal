<?php

namespace App\Livewire;

use Illuminate\View\ComponentAttributeBag;
use Livewire\Component;

class OptionSelector extends Component
{
    public $selectedOption = null;
    public $eventKey = 'select-option';
    public array $options = [];

    public function mount()
    {
        $this->selectedOption = session()->get($this->eventKey);
    }

    public function render()
    {
        return view('livewire.option-selector');
    }

    public function getExtraAttributeBag(): ComponentAttributeBag
    {
        return new ComponentAttributeBag();
    }

    public function optionSelected()
    {
        $this->dispatch("option-selected");

        session()->put($this->eventKey, $this->selectedOption);
    }
}
