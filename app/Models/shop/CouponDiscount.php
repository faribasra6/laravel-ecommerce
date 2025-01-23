<?php

namespace App\Models\shop;

use Illuminate\Database\Eloquent\Model;

class CouponDiscount extends Model
{
    protected $table = 'coupon_discount';
    protected $fillable = [
        'code',
        'name',
        'description',
        'max_use',
        'max_user_use',
        'discount_type',
        'discount_value',
        'minimum_order_amount',
        'start_date',
        'end_date',
        'status',
        'usage_count',
    ];

    protected $casts = [
        'max_use' => 'integer',
        'max_user_use' => 'integer',
        'discount_value' => 'float',
        'minimum_order_amount' => 'float',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
        'usage_count' => 'integer',
    ];

    public function isValid(): bool
    {
        $now = now();

        return $this->status &&
            ($this->start_date === null || $now->gte($this->start_date)) &&
            ($this->end_date === null || $now->lte($this->end_date)) &&
            $this->usage_count < $this->max_use;
    }
}
