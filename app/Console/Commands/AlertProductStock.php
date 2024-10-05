<?php

namespace App\Console\Commands;
use App\Models\User;
use App\Models\Product;
use App\Notifications\StockAlertNotification;
use Illuminate\Console\Command;

class AlertProductStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:out-of-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'launch out of stock alert';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products=Product::all();
        $admins = User::where('is_admin', true)->get();
        foreach ($products as $product) {

            if ($product->quantity < 10) {
                foreach ($admins as $admin) {

                    $admin->notify(new StockAlertNotification(['stock' => $product->quantity, 'product_name' => $product->product_name]));
                    
                }
            }
        }
    }
}
