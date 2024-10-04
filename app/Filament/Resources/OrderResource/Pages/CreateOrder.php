<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Reservation;
use Auth;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    use \App\Traits\RedirectIndex;
    protected static string $resource = OrderResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = $data['status'] ?? OrderStatus::ORDERED;
        $data['user_id'] = $data['user_id'] ?? Auth::user()->id;
        $data['market_id'] = Product::where('id', $data['product_id'])->first()->market_id;
        return parent::mutateFormDataBeforeCreate($data);
    }


    protected function afterCreate(): void
    {
        Reservation::where([['product_id', $this->data['product_id']], ['user_id', Auth::useR()?->id]])
            ->update(['status' => 1, 'reservation_expiry' => Carbon::now()->subHours(2)]);
    }

    protected function beforeCreate(): void
    {
        if (!Auth::user()->checkProductReservation($this->data['product_id'])) {
            Notification::make()
                ->title('Hey ' . Auth::user()?->name)
                ->body("Please reserve this product before order")
                ->icon('heroicon-o-information-circle')
                ->color("danger")
                ->send();
            $this->halt();
        }

        $product = Product::find($this->data['product_id']);

        $order = Order::join("products", "products.id", "=", "orders.product_id")
            ->where([
                ['customer_email', $this->data['customer_email']],
                ['orders.status', OrderStatus::ORDERED->value],
                ['products.amz_sold_by', $product->amz_sold_by]
            ])->first();

        if ($order != null) {
            Notification::make()
                ->title('Hey ' . Auth::user()?->name)
                ->body("This seller already purchase from this store.")
                ->icon('heroicon-o-information-circle')
                ->color("danger")
                ->send();
            $this->halt();
        }
    }
}
