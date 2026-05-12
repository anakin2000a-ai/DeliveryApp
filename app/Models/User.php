<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'created_by');
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function approvedOrders()
    {
        return $this->hasMany(Order::class, 'approved_by');
    }

    public function rejectedOrders()
    {
        return $this->hasMany(Order::class, 'rejected_by');
    }

    public function paymentsUpdated()
    {
        return $this->hasMany(Order::class, 'payment_updated_by');
    }

    public function adminActivityLogs()
    {
        return $this->hasMany(AdminActivityLog::class, 'admin_id');
    }
    public function contactMessages()
    {
        return $this->hasMany(ContactUs::class);
    }
}