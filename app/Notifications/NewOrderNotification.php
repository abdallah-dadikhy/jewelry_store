<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; // إشعار محفوظ في قاعدة البيانات
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'order',
            'message' => 'تم إنشاء طلب جديد برقم #' . $this->order->OrderID,
            'order_id' => $this->order->OrderID,
        ];
    }
}
