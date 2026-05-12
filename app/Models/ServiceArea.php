<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceArea extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'postal_code',
        'city',
        'center_lat',
        'center_lng',
        'radius_km',
        'polygon',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'center_lat' => 'decimal:8',
            'center_lng' => 'decimal:8',
            'radius_km' => 'decimal:2',
            'polygon' => 'array',

            'is_active' => 'boolean',
        ];
    }

    public function customerAddresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}