<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_number',
        'full_name',
        'national_id',
        'phone',
        'gender',
        'date_of_birth',
        'blood_group',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'known_allergies',
        'chronic_diseases',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class)->orderBy('appointment_date', 'desc');
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class)->orderBy('visit_date', 'desc');
    }

    public function getAgeAttribute(): int
    {
        return Carbon::parse($this->date_of_birth)->age;
    }

    public function getGenderArabicAttribute(): string
    {
        return match (strtolower($this->gender)) {
            'male', 'ذكر' => 'ذكر',
            'female', 'أنثى' => 'أنثى',
            default => $this->gender,
        };
    }
}
