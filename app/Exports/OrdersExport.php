<?php

namespace App\Exports;

use App\Models\Order;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use DB;
use Auth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    private string $from;
    private string $to;
    private string $status;

    public function __construct(string $from, string $to, string $status)
    {
        $this->from = $from;
        $this->to = $to;
        $this->status = $status;
    }

    public function headings(): array
    {
        return [
            'Creation Date',
            'PM Name',
            'Order ID',
            'Order Number',
            'Customer Email',
            'Market',
            'Invoice Image',
            'Review Type Commission',
            'Review Image',
            'Refund Image'
        ];
    }

    public function query()
    {
        if (Auth::user()->isPM()) {
            return Order::query()->whereBetween(FacadesDB::raw('DATE(created_at)'), [$this->from, $this->to])->where([['status', $this->status], ['user_id', Auth::user()->id]]);
        } else {
            return Order::query()->whereBetween(FacadesDB::raw('DATE(created_at)'), [$this->from, $this->to])->where('status', $this->status);
        }
    }

    public function prepareRows($rows)
    {
        $sum = 0;
        $rows->each(function ($row) use (&$sum) {
            $sum = $sum + $row->review_type_commission;
        });

        $rows->add([
            'is_summary' => true,
            'total_commission' => $sum
        ]);

        return $rows;
    }

    public function map($order): array
    {

        if (isset($order['is_summary']) && $order['is_summary'] === true) {
            // dd('sdsdsdsd', $order);
            //Return a summary order
            return [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Total Commission: ' . $order['total_commission'],

            ];
        } else {

            return [
                $order->created_at->format('Y-m-d'),
                $order->user->name,
                $order->id,
                $order->amz_order_number,
                $order->customer_email,
                $order->market?->market,
                $order->invoice_image,
                $order->review_type_commission,
                $order->review_image,
                $order->refund_image,
            ];
        }

        // if (isset($order->is_summary) && $order->is_summary === true) {
        //     //Return a summary order
        //     return [
        //         'Total Commission:',
        //         $order->total_commission
        //     ];
        // } else {
        //     //Return a normal data order

        // }
    }
}
