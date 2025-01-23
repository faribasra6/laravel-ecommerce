<?php

namespace App\Models\shop;

use App\Models\admin\Product;
use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    protected $table = 'order_items';
    
    // Fillable attributes
    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'qty',
        'price',
        'total',
    ];

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Automatically calculate total if not provided
    public function setTotalAttribute($value)
    {
        if (!isset($this->attributes['total'])) {
            $this->attributes['total'] = $this->qty * $this->price;
        }
    }

    // Format total as a float with 2 decimal places
    public function getTotalAttribute($value)
    {
        return number_format((float)$value, 2, '.', '');
    }
}
