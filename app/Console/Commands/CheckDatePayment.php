<?php

namespace App\Console\Commands;

use App\Models\Sell;
use App\Notifications\CheckPushDate;
use App\Notifications\CheckPushDateNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckDatePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-date-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Retrieve all sells related to the user
        $sells = Sell::where('check_date', '<', now())->get() ?? [];


        foreach ($sells as $sell) {
            // Trigger the CheckPushDate action if the condition is met
            Notification::send($sell->user, new CheckPushDateNotification($sell));    }
        }


}
