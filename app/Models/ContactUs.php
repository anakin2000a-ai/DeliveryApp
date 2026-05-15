<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactUs extends Model
{
    protected $table = 'contact_us';

    protected $fillable = [
        'user_id',
        'replied_by',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'admin_reply',
        'read_at',
        'replied_at',
        'closed_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'replied_at' => 'datetime',
            'closed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function repliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}