<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name',            // Changed from category_name to name
        'slug',
        'image',
        'status',
        'showHome',        // Ensure consistency with the database column name
    ];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
