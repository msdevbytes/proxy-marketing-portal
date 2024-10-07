<?php

namespace App\Livewire;

use Auth;
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
            "Active Products" => metricSuffix($stats->total_active),
            "Disabled Products" => metricSuffix($stats->total_disabled)
        ];
        $this->data = collect([
            ['icon' => 'bi-compass', 'title' => 'Today: Active / Disabled ', 'value' => sprintf("%s / %s", metricSuffix($stats->todays_active), metricSuffix($stats->todays_disabled))],
            ['icon' => 'bi-calendar-check', 'title' => Carbon::now()->monthName . ': Active / Disabled', 'value' => sprintf("%s / %s", metricSuffix($stats->monthly_active), metricSuffix($stats->monthly_disabled))],
            ['icon' => 'bi-calendar4-event', 'title' => 'Overall: Active / Disabled', 'value' => sprintf("%s / %s", metricSuffix($stats->total_active), metricSuffix($stats->total_disabled))],
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

        $stats =  DB::table('products')
            ->selectRaw("
            SUM(CASE WHEN products.status = 1 THEN 1 ELSE 0 END) as total_active,
            SUM(CASE WHEN products.status = 0 THEN 1 ELSE 0 END) as total_disabled,
            SUM(CASE WHEN products.status = 1 AND DATE(products.created_at) = ? THEN 1 ELSE 0 END) as todays_active,
            SUM(CASE WHEN products.status = 0 AND DATE(products.created_at) = ? THEN 1 ELSE 0 END) as todays_disabled,
            SUM(CASE WHEN products.status = 1 AND MONTH(products.created_at) = ? THEN 1 ELSE 0 END) as monthly_active,
            SUM(CASE WHEN products.status = 0 AND MONTH(products.created_at) = ? THEN 1 ELSE 0 END) as monthly_disabled
        ", [$today, $today, $currentMonth, $currentMonth]);

        if (Auth::user()->isPMM()) {
            $stats = $stats->where('products.user_id', Auth::user()->id)->first();
        } else if (Auth::user()->isSuperAdmin() || Auth::user()->isPM()) {
            $stats = $stats->first();
        }

        return $stats;
    }
}
