<?php

namespace App\Models\shop;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'countries';
    protected $fillable = [
        'name',
        'code',       // Primary code for the country (e.g., 'US', 'CA')
    ];

    // Define relationships if the country is used in multiple contexts
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function customerAddresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }
    public function shippingCharges()
    {
        return $this->hasMany(Shipping::class, 'country_id');
    }
}
