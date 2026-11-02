<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'auditable_type',
        'auditable_id',
        'old_values',
        'new_values',
        'ip_address',
        'request_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
            'created_at' => 'datetime',
        ];
    }
// Define relationships
// An audit log belongs to a user (the one who performed the action)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    //morphTo() بيربط الموديل الحالي بأنواع Models مختلفة.
    // في هذه الحالة، يمكن أن يكون الـ AuditLog مرتبط بأي موديل آخر (مثل Invoice, Payment, Patient, إلخ) من خلال الحقول auditable_type و auditable_id.
    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }
}
