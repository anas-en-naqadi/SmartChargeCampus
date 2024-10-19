<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpirationDateNotification extends Notification
{
    use Queueable;

    private Product $product ;

    /**
     * Create a new notification instance.
     */
    public function __construct(Product $product)
    {
            $this->product = $product;
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
'message' => "كمية المنتج <span style='font-weight: bold; color: red;'>{$this->product->name}</span> أقل من 10، يرجى التحقق منها",
            'url' => "products"
        ];


    }
}
