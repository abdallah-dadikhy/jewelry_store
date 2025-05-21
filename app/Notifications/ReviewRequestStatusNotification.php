<?php
namespace App\Notifications;

use Illuminate\Notifications\Notification;

class ReviewRequestStatusNotification extends Notification
{
    public $status;
    public $comment;
    public $review;

    public function __construct($status, $comment = null, $review = null)
    {
        $this->status = $status;
        $this->comment = $comment;
        $this->review = $review;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        if ($this->status === 'new_request') {
            return [
                'title' => 'طلب مراجعة منتج جديد',
                'message' => 'تم تقديم طلب جديد: ' . $this->review->ProductName,
                'review_id' => $this->review->ReviewID,
            ];
        } elseif ($this->status === 'approved') {
            return [
                'title' => 'تمت الموافقة على طلبك',
                'message' => 'تمت الموافقة على طلب منتجك.',
            ];
        } elseif ($this->status === 'rejected') {
            return [
                'title' => 'تم رفض طلبك',
                'message' => 'تم رفض طلب منتجك. السبب: ' . $this->comment,
            ];
        }
    }
}
