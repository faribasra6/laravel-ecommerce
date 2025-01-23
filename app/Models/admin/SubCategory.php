<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = [
        'category_id', 
        'name', 
        'slug', 
        'status', 
        'showHome',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeActive($query)
{
    return $query->where('status', 1);
}
}
