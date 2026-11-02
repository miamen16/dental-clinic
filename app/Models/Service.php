<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
        'is_active',
        'tax_id', // Add tax_id to the fillable array
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function appointments(): HasMany
    {
        // A service can be associated with many appointments
        return $this->hasMany(Appointment::class);
    }

    public function treatmentItems(): HasMany
    {
        // A service can be associated with many treatment items
        return $this->hasMany(TreatmentItem::class);
    }

    public function invoiceItems(): HasMany
    {
        //
        return $this->hasMany(InvoiceItem::class);
    }
    // Get the tax associated with the service
    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
