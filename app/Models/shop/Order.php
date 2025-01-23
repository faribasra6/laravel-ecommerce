<?php

namespace App\Models\shop;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    const STATUS_PENDING = 'pending';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    public static $validStatuses = [
        self::STATUS_PENDING,
        self::STATUS_SHIPPED,
        self::STATUS_DELIVERED,
        self::STATUS_CANCELLED,
    ];
    protected $fillable = [
        'user_id',
        'subtotal',
        'shipping',
        'coupon_code',
        'discount',
        'grand_total',
        'status',
        'payment_method',
        'payment_status',
        'transaction_id',
        'payment_gateway',
        'order_type',
        'tax',
        'estimated_delivery',
        'refund_status',
        'refund_amount',
        'tracking_number',
        'coupon_discount',
        'metadata',
        'first_name',
        'last_name',
        'email',
        'mobile',
        'country_id',
        'address',
        'apartment',
        'city',
        'state',
        'zip',
        'notes',
    ];
    

    
    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItems::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }
    protected $casts = [
        'metadata' => 'array',
        'estimated_delivery' => 'datetime', // If stored as a timestamp
    ];
    public function shipping()
    {
        return $this->belongsTo(Shipping::class, 'shipping_id'); // Assuming the shipping relation is stored in 'shipping_id' in the orders table
    }
    
    
}
