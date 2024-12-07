<?php

use App\Filament\Pages\ProductListForBuyer;
use Illuminate\Support\Facades\Route;

Route::get('/', ProductListForBuyer::class);
