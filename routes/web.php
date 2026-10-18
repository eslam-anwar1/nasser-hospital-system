<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\MedicalRecordController;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('home');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Patients (Available to Admin, Doctor, Receptionist)
    Route::resource('patients', PatientController::class);

    // Appointments (Available to Admin, Doctor, Receptionist)
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');

    // Medical Records / Consultations (Admin, Doctor, Receptionist can view)
    Route::resource('medical-records', MedicalRecordController::class);

    // Clinics Management (Admin, Receptionist, Doctor can view)
    Route::resource('clinics', ClinicController::class)->except(['create', 'edit', 'show']);

    // Staff / User Management (Admin only)
    Route::middleware('role:Admin')->group(function () {
        Route::resource('users', UserController::class);
    });
});
