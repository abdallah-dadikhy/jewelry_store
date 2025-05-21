<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewProductNotification extends Notification
{
    use Queueable;

    protected $product;

    public function __construct($product)
    {
        $this->product = $product;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'product',
            'message' => 'تمت إضافة منتج جديد بإسم: ' . $this->product->Name,
            'product_id' => $this->product->ProductID,
        ];
    }
}
