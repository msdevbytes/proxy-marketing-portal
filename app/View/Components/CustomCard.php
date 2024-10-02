<?php

namespace App\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Illuminate\View\View;

class CustomCard extends Component
{
    /**
     * Create the component instance.
     */
    public function __construct(
        public string $bgColor,
        public string $icon,
        public string $title,
        public string $description,
        public string $link,
        public array $header,
        public Collection $data
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.stats.custom-card');
    }
}
