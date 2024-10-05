<?php

namespace App\Filament\Exports;

use App\Models\Order;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class OrderExporter extends Exporter
{
    protected static ?string $model = Order::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('created_at')->label('creation date'),
            ExportColumn::make('user.name')->label('PM Name'),
            ExportColumn::make('id')->label('Order ID'),
            ExportColumn::make('amz_order_number')->label('Order Number'),
            ExportColumn::make('customer_email'),
            ExportColumn::make('market.market'),
            ExportColumn::make('invoice_image'),
            ExportColumn::make('review_type_commission'),
            // ExportColumn::make('status'),
            ExportColumn::make('review_image'),
            ExportColumn::make('refund_image'),
            // ExportColumn::make('buyer_verification_image'),
            // ExportColumn::make('review_link'),
            // ExportColumn::make('remarks'),
            // ExportColumn::make('product.name'),
            // ExportColumn::make('product')->label('Seller')->state(function (Order $query) {
            //     return $query->with(['product.user'])->first()?->user?->name;
            // }),
            // ExportColumn::make('product')->label('Seller ID')->state(function (Order $query) {
            //     return $query->with(['product.user'])->first()?->user?->id;
            // }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your order export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
