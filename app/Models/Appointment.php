<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_number',
        'patient_id',
        'doctor_id',
        'clinic_id',
        'appointment_date',
        'status',
        'priority',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'datetime',
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

    public function medicalRecord(): HasOne
    {
        return $this->hasOne(MedicalRecord::class);
    }

    public function getStatusArabicAttribute(): string
    {
        return match ($this->status) {
            'Pending' => 'قيد الانتظار',
            'Confirmed' => 'مؤكد',
            'Completed' => 'مكتمل ومفحوص',
            'Cancelled' => 'ملغي',
            default => $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'Pending' => 'bg-amber-100 text-amber-800 border-amber-300',
            'Confirmed' => 'bg-blue-100 text-blue-800 border-blue-300',
            'Completed' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
            'Cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }
}
