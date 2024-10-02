<?php

namespace App\Livewire;

use Carbon\Carbon;
use Coduo\PHPHumanizer\NumberHumanizer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductStats extends Component
{


    public $title = 'Custom Stats';
    public $bgColor = 'bg-info';
    public string $icon;
    public string $description;
    public string $link;
    public array $header;
    public array $filterOptions;
    public Collection $data;
    public string $class;

    function mount()
    {
        $stats = $this->getProductSummaryByStatus();

        $this->title = 'Product Status Summary';
        $this->bgColor = 'bg-info';
        $this->icon = 'bi-bar-chart-line-fill';
        $this->description = 'Custom stats description';
        $this->link = 'product-stats';
        $this->header = [
            "Active Products" => NumberHumanizer::metricSuffix($stats->total_active),
            "Disabled Products" => NumberHumanizer::metricSuffix($stats->total_disabled)
        ];
        $this->data = collect([
            ['icon' => 'bi-compass', 'title' => 'Today: Active / Disabled ', 'value' => sprintf("%s / %s", NumberHumanizer::metricSuffix($stats->todays_active), NumberHumanizer::metricSuffix($stats->todays_disabled))],
            ['icon' => 'bi-calendar-check', 'title' => Carbon::now()->monthName . ': Active / Disabled', 'value' => sprintf("%s / %s", NumberHumanizer::metricSuffix($stats->monthly_active), NumberHumanizer::metricSuffix($stats->monthly_disabled))],
            ['icon' => 'bi-calendar4-event', 'title' => 'Overall: Active / Disabled', 'value' => sprintf("%s / %s", NumberHumanizer::metricSuffix($stats->total_active), NumberHumanizer::metricSuffix($stats->total_disabled))],
        ]);
    }

    public function render()
    {
        return view('livewire.product-stats');
    }

    function getProductSummaryByStatus()
    {
        $today = Carbon::today();
        $currentMonth = Carbon::now()->month;

        return DB::table('products')
            ->selectRaw("
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as total_active,
            SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as total_disabled,
            SUM(CASE WHEN status = 1 AND DATE(created_at) = ? THEN 1 ELSE 0 END) as todays_active,
            SUM(CASE WHEN status = 0 AND DATE(created_at) = ? THEN 1 ELSE 0 END) as todays_disabled,
            SUM(CASE WHEN status = 1 AND MONTH(created_at) = ? THEN 1 ELSE 0 END) as monthly_active,
            SUM(CASE WHEN status = 0 AND MONTH(created_at) = ? THEN 1 ELSE 0 END) as monthly_disabled
        ", [$today, $today, $currentMonth, $currentMonth])
            ->first();
    }
}
