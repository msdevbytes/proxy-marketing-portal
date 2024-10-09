<?php

namespace App\Livewire;

use App\Enums\OrderStatus;
use App\Exports\OrdersExport;
use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Livewire\Component;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;
use Malzariey\FilamentDaterangepickerFilter\Fields\DateRangePicker;
use Str;

class OrdersReporting extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function render()
    {
        return view('livewire.orders-reporting');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    DateRangePicker::make('date_range')
                        ->autoApply(false)
                        ->required()
                        ->separator(' | ')
                        ->displayFormat('Y-MM-d')
                        ->format('Y-MM-d'),
                    Select::make('status')
                        ->label('Order Status')
                        ->options(function () {
                            $status = [];
                            foreach (OrderStatus::cases() as $case) {
                                $status[$case->value] = $case->value;
                            }

                            return $status;
                        })
                        ->required()
                        ->native(false),

                ])->columns([
                    'md' => 2,
                    'sm' => 1
                ])
            ])
            ->statePath('data')
        ;
    }

    public function create()
    {
        $d = $this->form->getState();
        $dateRange = explode(' | ', $d['date_range']);
        // dd($dateRange);
        $from = Carbon::parse($dateRange[0])->format('Y-m-d');
        $to = Carbon::parse($dateRange[1])->format('Y-m-d');

        return Excel::download(new OrdersExport($from, $to, $d['status']), Str::replace(' ', '', $d['date_range']) . '-orders.csv', ExcelExcel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
