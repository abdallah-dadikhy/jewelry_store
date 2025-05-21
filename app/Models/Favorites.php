<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorites extends Model
{
    use HasFactory;
    protected $table = 'favorites';
    protected $primaryKey = 'FavoriteID'; 
    public $timestamps = false;
    protected $fillable = [
        'UserID',
        'ProductID'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'UserID');
    }
    
    public function product() {
        return $this->belongsTo(Product::class, 'ProductID');
    }
}
