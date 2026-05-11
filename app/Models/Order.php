<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_number',
        'customer_id',
        'restaurant_id',
        'customer_address_id',
        'service_area_id',

        'delivery_address',
        'delivery_city',
        'delivery_postal_code',
        'delivery_country',
        'delivery_latitude',
        'delivery_longitude',

        'customer_note',

        'status',
        'payment_status',
        'loss_status',

        'items_total',
        'delivery_cost',
        'order_total',
        'paid_amount',
        'loss_amount',

        'pending_at',
        'approved_at',
        'rejected_at',
        'auto_rejected_at',
        'requested_at',
        'paid_at',
        'not_paid_at',
        'expires_at',

        'approved_by',
        'rejected_by',
        'payment_updated_by',

        'admin_rejection_reason',
        'admin_payment_note',
        'loss_reason',

        'estimated_delivery_time',
    ];

    protected function casts(): array
    {
        return [
            'delivery_latitude' => 'decimal:8',
            'delivery_longitude' => 'decimal:8',

            'items_total' => 'decimal:2',
            'delivery_cost' => 'decimal:2',
            'order_total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'loss_amount' => 'decimal:2',

            'pending_at' => 'datetime',
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'auto_rejected_at' => 'datetime',
            'requested_at' => 'datetime',
            'paid_at' => 'datetime',
            'not_paid_at' => 'datetime',
            'expires_at' => 'datetime',
            'estimated_delivery_time' => 'datetime',
        ];
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function customerAddress()
    {
        return $this->belongsTo(CustomerAddress::class);
    }

    public function serviceArea()
    {
        return $this->belongsTo(ServiceArea::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function paymentUpdatedBy()
    {
        return $this->belongsTo(User::class, 'payment_updated_by');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function losses()
    {
        return $this->hasMany(Loss::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }
}