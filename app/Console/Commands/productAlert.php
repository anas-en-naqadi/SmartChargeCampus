<?php

namespace App\Console\Commands;
use App\Models\User;
use App\Models\Product;
use App\Notifications\ExpirationDate;
use App\Notifications\ExpirationDateNotification;
use App\Notifications\StockAlertNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class ProductAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:product-checks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'launch out of stock alert';

    public function hasExpired($product)
{
    return $product->expiration_date && Carbon::parse($product->expiration_date)->lt(now());
}

    /**
     * Execute the console command.
     */
    public function handle()
{
    // Fetch products with quantity less than 10 for stock alert
    $lowStockProducts = Product::where('stock_quantity', '<', 10)->get() ?? [];

    foreach ($lowStockProducts as $product) {
        Notification::send($product->user, new StockAlertNotification($product));
       }

    // Fetch expired products
    $expiredProducts = Product::whereDate('expiration_date', '<', now())->get();
    foreach ($expiredProducts as $product) {
        Notification::send($product->user, new ExpirationDateNotification($product));    }
    }


}
