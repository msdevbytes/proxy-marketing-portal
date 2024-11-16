<?php

namespace App\Livewire;

use App\Models\Bonus as ModelsBonus;
use Livewire\Component;

class Bonus extends Component
{

    private $bonuses;

    public function mount()
    {
        $this->bonuses = ModelsBonus::where('status', 1)->get();
    }
    public function render()
    {
        return view('livewire.bonus', ['bonuses' =>  $this->bonuses]);
    }
}
