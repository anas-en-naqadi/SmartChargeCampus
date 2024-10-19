<?php

namespace App\Notifications;

use App\Models\Sell;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CheckPushDateNotification extends Notification
{
    use Queueable;

    private Sell $product;
    /**
     * Create a new notification instance.
     */
    public function __construct(Sell $product)
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
'message' => "<span style='font-weight: bold; color: red;'>{$this->product->client->name}</span> يجب عليك دفع الشيك للبنك غدا بإسم الزبون",
            'url' => "sells"
        ];
    }
}
