<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
class Patient extends Model
{
    //
        protected $fillable = [
        'patient_number',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'date_of_birth',
        'address',
        'gender',
        'blood_type',
        'medical_history',
        'allergies',
        'current_medications',
        'notes',
    ];


    // retun the date of birth in a specific format
    protected function casts(): array
    {
    return [
        'date_of_birth' => 'date',
    ];
    }
    // patient has many appointments
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    // patient has many medical records
    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }

    // patient has many treatmentPlans
    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(TreatmentPlan::class);
    }

    // patient has many invoices
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => trim(
                $this->first_name . ' ' . $this->last_name
            ),
        );
    }
}