<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_DOCTOR = 'doctor';
    public const ROLE_RECEPTIONIST = 'receptionist';

    public const ROLES = [
        self::ROLE_ADMIN,
        self::ROLE_DOCTOR,
        self::ROLE_RECEPTIONIST,
    ];

    public const SPECIALIZATIONS = [
        'General Dentistry',
        'Orthodontics',
        'Endodontics',
        'Periodontics',
        'Prosthodontics',
        'Oral Surgery',
        'Pediatric Dentistry',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',

        // doctor profile
        'first_name',
        'last_name',
        'phone',
        'date_of_birth',
        'specialty',
        'experience',
        'is_active',
        'gender',
        'bio',
        'address',
        'city',
        'country',
        'postal_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        // Define how certain attributes should be cast to native types
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_of_birth' => 'date',
            'experience' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    // only users with the doctor role
    public function scopeDoctors(Builder $query): Builder
    {
        // Filter users to only those with the doctor role
        return $query->where('role', self::ROLE_DOCTOR);
    }

    // only users with the receptionist role
    public function scopeReceptionists(Builder $query): Builder
    {
        return $query->where('role', self::ROLE_RECEPTIONIST);
    }

    public function isDoctor(): bool
    {
        // Check if the user's role is doctor
        return $this->role === self::ROLE_DOCTOR;
    }

    // keep "name" (used by login/profile) in sync with first + last name
    protected static function booted(): void
    {
        // When saving a user, if first_name or last_name is set, update the name attribute
        static::saving(function (User $user) {
            if ($user->first_name || $user->last_name) {
                $user->name = trim($user->first_name . ' ' . $user->last_name);
            }
        });
    }
    public function dentistAppointments(): HasMany
    {
        // A dentist (user) has many appointments
        return $this->hasMany(Appointment::class, 'dentist_id');
    }

    public function createdAppointments(): HasMany
    {
        // A user (receptionist or admin) has many appointments they created
        return $this->hasMany(Appointment::class, 'created_by');
    }

    public function medicalRecords(): HasMany
    {
        // A dentist (user) has many medical records
        return $this->hasMany(MedicalRecord::class, 'dentist_id');
    }

    public function treatmentPlans(): HasMany
    {
        // A dentist (user) has many treatment plans
        return $this->hasMany(TreatmentPlan::class, 'dentist_id');
    }

    public function receivedPayments(): HasMany
    {
        // A user (receptionist or admin) has many payments they received
        return $this->hasMany(Payment::class, 'received_by');
    }

    public function auditLogs(): HasMany
    {
        // A user has many audit logs
        return $this->hasMany(AuditLog::class);
    }
}
