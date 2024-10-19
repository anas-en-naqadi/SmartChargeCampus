<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StockAlertNotification extends Notification
{
    use Queueable;

    private Product $details ;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $details)
    {
        $this->details = $details;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }


    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
'message' => "كمية المنتج <span style='font-weight: bold; color: red;'>{$this->details->name}</span> أقل من 10، يرجى التحقق منها",
            'url' => "products"
        ];
    }
}
