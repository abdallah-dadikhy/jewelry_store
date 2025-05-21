<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $primaryKey = 'OrderID';  
    protected $fillable = [
        'UserID',
        'OrderDate',
        'Status',
        'TotalAmount',
        'ShippingAddress',
        'PaymentMethod'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'UserID');
    }
    
    public function orderDetails() {
        return $this->hasMany(OrderDetails::class, 'OrderID');
    }
}
