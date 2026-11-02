<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TreatmentPlan extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'patient_id',
        'dentist_id',
        'appointment_id',
        'title',
        'diagnosis',
        'notes',
        'status',
        'start_date',
        'end_date',
    ];
    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function dentist()
    {
        return $this->belongsTo(User::class, 'dentist_id');
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);  
      
    }
    public function treatmentItems()
    {
        return $this->hasMany(TreatmentItem::class);
    }
}
