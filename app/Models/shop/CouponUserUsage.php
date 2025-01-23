<?php

namespace App\Models\shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CouponUserUsage extends Model
{
    protected $table = 'coupon_user_usage';
    protected $fillable = [
        'coupon_discount_id',
        'user_id',
    ];

    public function couponDiscount()
    {
        return $this->belongsTo(CouponDiscount::class, 'coupon_discount_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
