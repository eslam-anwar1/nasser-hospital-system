<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'record_number',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'clinic_id',
        'chief_complaint',
        'diagnosis',
        'prescription',
        'doctor_notes',
        'blood_pressure',
        'pulse_rate',
        'temperature',
        'weight',
        'visit_date',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'weight' => 'decimal:2',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }
}
