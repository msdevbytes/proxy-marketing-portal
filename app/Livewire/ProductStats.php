<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class ProductStats extends Component
{
    public $title = 'Custom Stats';
    public $bgColor = 'bg-info';
    public string $icon;
    public string $description;
    public string $link;
    public array $header;
    public Collection $data;

    function mount()
    {
        $this->title = 'Product Status Summary';
        $this->bgColor = 'bg-info';
        $this->icon = 'bi-bar-chart-line-fill';
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
        return view('livewire.product-stats');
    }
}
