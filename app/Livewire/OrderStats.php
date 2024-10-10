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

    public $title = '';
    public $bgColor = 'bg-red';
    public string $icon;
    public string $description;
    public string $link;
    public array $header;
    public array $filterOptions;
    public Collection $data;
    public string $class;
    public string $orderStatus = OrderStatus::ORDERED->value;

    function mount()
    {
        $this->filterOptions = OrderStatus::toArray();
        $stats = $this->getProductSummaryByStatus();
        $this->icon = 'bi-bar-chart-line-fill';
        $this->description = 'Custom stats description';
        $this->link = 'product-stats';
        $this->header = [
            "Revenue Comission" => metricSuffix($stats?->total_commission),
        ];
        $this->data = collect([
            ['icon' => 'bi-compass', 'title' => 'Today', 'value' =>  metricSuffix($stats?->todays_total)],
            ['icon' => 'bi-calendar-check', 'title' => Carbon::now()->monthName, 'value' => metricSuffix($stats?->month_total)],
            ['icon' => 'bi-calendar4-event', 'title' => 'Overall', 'value' => metricSuffix($stats?->total_commission)],
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

        $stats = DB::table('products')->join('orders', 'orders.product_id', '=', 'products.id')
            ->selectRaw("
            if(sum(products.commission), sum(products.commission), 0) as total_commission,
            SUM(CASE WHEN orders.status = '" . $this->orderStatus . "' AND DATE(orders.created_at) = ? THEN 1 ELSE 0 END) as todays_total,
            SUM(CASE WHEN orders.status = '" . $this->orderStatus . "' AND MONTH(orders.created_at) = ? THEN 1 ELSE 0 END) as month_total
        ", [$today, $currentMonth])->where('orders.status', '=', $this->orderStatus);

        if (Auth::user()->isSuperAdmin() || Auth::user()->isManager()) {

            dump(Auth::user()->isManager());
            $stats = $stats->first();
        } else if (Auth::user()->isPMM()) {
            $stats = $stats->where('products.user_id', Auth::user()->id)->first();
        } else {
            $stats = $stats->where('orders.user_id', Auth::user()->id)->first();
        }

        return $stats;
    }
}
