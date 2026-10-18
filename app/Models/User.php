<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employee_id',
        'name',
        'username',
        'email',
        'password',
        'role',
        'specialization',
        'phone',
        'is_active',
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
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }

    // Role helper methods
    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'Doctor';
    }

    public function isReceptionist(): bool
    {
        return $this->role === 'Receptionist';
    }

    public function isAccountant(): bool
    {
        return $this->role === 'Accountant';
    }

    public function getRoleArabicAttribute(): string
    {
        return match ($this->role) {
            'Admin' => 'مدير النظام',
            'Doctor' => 'طبيب استشاري / أخصائي',
            'Receptionist' => 'موظف استقبال وتنسيق',
            'Accountant' => 'محاسب مالي',
            default => 'موظف',
        };
    }
}
