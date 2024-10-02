<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class OrderStats extends Component
{
    public $title = 'Order Stats';
    public $bgColor = 'bg-red';
    public string $icon;
    public string $description;
    public string $link;
    public array $header;
    public Collection $data;

    function mount()
    {
        $this->icon = 'bi bi-bar-chart-line-fill';
        $this->description = 'Custom stats description';
        $this->link = 'product-stats';
        $this->header = [];
        $this->data = collect([
            ['icon' => 'heroicon-o-square-3-stack-3d', 'title' => 'Total Products', 'value' => "703 / 703"],
            ['icon' => 'heroicon-o-square-3-stack-3d', 'title' => 'Total Products', 'value' => 703.00],
            ['icon' => 'heroicon-o-square-3-stack-3d', 'title' => 'Total Products', 'value' => 703.00],
        ]);
    }

    public function render()
    {
        return view('livewire.order-stats');
    }
}
