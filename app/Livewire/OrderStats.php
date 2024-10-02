<?php

namespace App\Livewire;

use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $stats = $this->getProductSummaryByStatus();

        $this->title = 'Order Status Summary';
        $this->bgColor = 'bg-green';
        $this->icon = 'bi-bar-chart-line-fill';
        $this->description = 'Custom stats description';
        $this->link = 'product-stats';
        $this->header = [
            "Active Orders" => metricSuffix($stats->total_active),
            "Disabled Orders" => metricSuffix($stats->total_disabled)
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

        $stats = DB::table('orders')
            ->selectRaw("
            SUM(CASE WHEN status = '" . OrderStatus::ORDERED->value . "' THEN 1 ELSE 0 END) as total_active,
            SUM(CASE WHEN status = '" . OrderStatus::ORDERED->value . "' THEN 1 ELSE 0 END) as total_disabled,
            SUM(CASE WHEN status = '" . OrderStatus::ORDERED->value . "' AND DATE(created_at) = ? THEN 1 ELSE 0 END) as todays_active,
            SUM(CASE WHEN status = '" . OrderStatus::ORDERED->value . "' AND DATE(created_at) = ? THEN 1 ELSE 0 END) as todays_disabled,
            SUM(CASE WHEN status = '" . OrderStatus::ORDERED->value . "' AND MONTH(created_at) = ? THEN 1 ELSE 0 END) as monthly_active,
            SUM(CASE WHEN status = '" . OrderStatus::ORDERED->value . "' AND MONTH(created_at) = ? THEN 1 ELSE 0 END) as monthly_disabled
        ", [$today, $today, $currentMonth, $currentMonth]);

        if (Auth::user()->isSuperAdmin()) {
            $stats = $stats->first();
        } else {
            $stats = $stats->where('user_id', Auth::user()->id)->first();
        }

        return $stats;
    }
}
