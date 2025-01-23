<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Brands extends Model
{
    protected $table = 'brands';
    protected $fillable = [
        'name',
        'slug',             // Added to allow mass assignment
        'status',
    ];

    public function getStatusLabelAttribute()
    {
        return $this->status ? 'Active' : 'Inactive';
    }

    
}
