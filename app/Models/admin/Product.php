<?php

namespace App\Models\admin;

use App\Models\shop\Order;
use App\Models\shop\OrderItems;
use App\Models\shop\ProductRating;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    
    protected $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'shipping_returns',
        'related_products',
        'price',
        'compare_price',
        'category_id',
        'subcategory_id',
        'brand_id',
        'is_featured',
        'sku',
        'barcode',
        'track_qty',
        'qty',
        'status',
    ];

    // Define the relationship to the OrderItem model (for counting and accessing order items)
    public function orderItems()
    {
        return $this->hasMany(OrderItems::class);
    }
    public function ratings()
    {
        return $this->hasMany(ProductRating::class)->where('status', 'approved');
    }
    

    // Access the orders through the orderItems relationship
    public function orders()
    {
        return $this->hasMany(Order::class);
    }


    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brands::class);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
    }

    public function getIsFeaturedAttribute($value)
    {
        return (bool) $value;
    }
}
