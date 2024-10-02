<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class CustomStatsComponent extends Component
{

    public $title = 'Custom Stats';
    public $bgColor = 'bg-blue';
    public string $icon;
    public string $description;
    public string $link;
    public array $header;
    public Collection $data;

    function mount()
    {
        $this->header = ['Active Products' => 703.00, 'Disabled Products' => 800.55];
        $this->data = collect([
            ['icon' => 'heroicon-o-square-3-stack-3d', 'title' => 'Total Products', 'value' => "703 / 703"],
            ['icon' => 'heroicon-o-square-3-stack-3d', 'title' => 'Total Products', 'value' => 703.00],
            ['icon' => 'heroicon-o-square-3-stack-3d', 'title' => 'Total Products', 'value' => 703.00],
        ]);
    }

    // Render the component
    public function render()
    {
        return view('livewire.custom-stats-component');
    }
}
