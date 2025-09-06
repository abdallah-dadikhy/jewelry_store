<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $primaryKey = 'ProductID'; 
    protected $fillable = [
        'Name',
        'Description',
        'CategoryID',
        'karat',
        'Price',
        'quantity',
        'ProductFile',
        'SellerID',
        'IsFeatured'
    ];

    public function category() {
        return $this->belongsTo(ProductCategory::class, 'CategoryID');
    }
    
    public function seller() {
        return $this->belongsTo(User::class, 'SellerID');
    }
    
    public function orderDetails() {
        return $this->hasMany(OrderDetails::class, 'ProductID');
    }
    
    public function favorites() {
        return $this->hasMany(Favorites::class, 'ProductID');
    }
    

}
