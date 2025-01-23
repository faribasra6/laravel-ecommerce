<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';
    protected $fillable = [
        'product_id',
        'title',
        'path',
        'type',
        'alt_text',
        'sort_order',
        'is_primary',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFullPathAttribute()
    {
        return url('storage/' . $this->path);
    }

    public function markAsPrimary()
    {
        $this->update(['is_primary' => true]);
    }

}
