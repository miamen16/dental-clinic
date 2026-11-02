<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    public const STATUSES = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'no_show' => 'No Show',
    ];

    // Bootstrap badge colour for each status
    public const STATUS_COLORS = [
        'pending' => 'warning',
        'confirmed' => 'primary',
        'completed' => 'success',
        'cancelled' => 'secondary',
        'no_show' => 'danger',
    ];

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function statusColor(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    protected $fillable = [
        'patient_id',
        'service_id',
        'dentist_id',
        'start_at',
        'end_at',
        'status',
        'reason',
        'notes',
        'created_by',
        
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    // Define relationships
    public function patient(): BelongsTo
    {
        // An appointment belongs to a patient
        return $this->belongsTo(Patient::class);
    }

    public function service(): BelongsTo
    {
        // An appointment is booked for a service
        return $this->belongsTo(Service::class);
    }

    public function dentist(): BelongsTo
    {
        // An appointment belongs to a dentist (user)
        return $this->belongsTo(User::class, 'dentist_id');
    }

    public function creator(): BelongsTo
    {
        // An appointment belongs to a creator (user)
        return $this->belongsTo(User::class, 'created_by');
    }

    public function medicalRecord(): HasOne
    {
        // An appointment has one medical record
        return $this->hasOne(MedicalRecord::class);
    }
}
