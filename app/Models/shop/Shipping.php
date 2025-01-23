<?php

namespace App\Models\shop;

use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'shipping_charges';

    protected $fillable = [
        'country_id', 
        'amount',
    ];
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
