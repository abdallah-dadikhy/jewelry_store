<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReviewRequest extends Model
{
    use HasFactory;
    protected $table = 'review_requests';
    protected $primaryKey = 'ReviewID';
    protected $fillable = [
        'UserID',
        'ProductName',
        'ProductDescription',
        'ProductWeight',
        'ProductPrice',
        'ProductImages',
        'Status',
        'AdminComments'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'UserID');
    }
    
    // علاقة مع Admin (اختيارية)
    public function admin() {
        return $this->belongsTo(User::class, 'AdminID'); // إذا كان لديك حقل AdminID
    }
}
