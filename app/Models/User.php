<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $primaryKey = 'UserID';
    public $incrementing = true;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'userType',
        'preferredLanguage',
        'preferredTheme'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->password;
    }

    
    public function orders() {
        return $this->hasMany(Order::class, 'UserID');
    }
    
    public function favorites() {
        return $this->hasMany(Favorites::class, 'UserID');
    }
    
    public function reviewRequests() {
        return $this->hasMany(ReviewRequest::class, 'UserID');
    }
    
    // إذا كان المستخدم بائعًا (Seller)
    public function products() {
        return $this->hasMany(Product::class, 'SellerID');
    }
}
