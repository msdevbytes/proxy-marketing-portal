<div>
    {{ $getRecord()->sale_limit_overall -$getRecord()->orders()->where('orders.status', '!=', App\Enums\OrderStatus::CANCELLED->value)->count() }}
</div>
