<?php

namespace App\Models\shop;

use App\Models\admin\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $table = 'product_rating';
    protected $fillable = [
        'username',
        'email',
        'product_id',
        'user_id',
        'rating',
        'review',
        'status',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class); // assuming you have a Product model
    }
    public function user()
    {
        return $this->belongsTo(User::class); // assuming you have a User model
    }
    public function getStatusAttribute($value)
    {
        return ucfirst($value); // Capitalize the first letter of the status value
    }
    public function setRatingAttribute($value)
    {
        $this->attributes['rating'] = (float) $value;
    }
}
