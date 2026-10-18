<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Clinic;
use App\Models\MedicalRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'total_patients' => Patient::count(),
            'today_appointments' => Appointment::whereDate('appointment_date', $today)->count(),
            'pending_appointments' => Appointment::where('status', 'Pending')->count(),
            'completed_appointments' => Appointment::where('status', 'Completed')->count(),
            'active_doctors' => User::where('role', 'Doctor')->where('is_active', true)->count(),
            'active_clinics' => Clinic::where('is_active', true)->count(),
            'total_records' => MedicalRecord::count(),
        ];

        // Recent appointments
        $recentAppointments = Appointment::with(['patient', 'doctor', 'clinic'])
            ->orderBy('appointment_date', 'desc')
            ->take(6)
            ->get();

        // Recent registered patients
        $recentPatients = Patient::latest()->take(5)->get();

        // Today's schedule
        $todayAppointments = Appointment::with(['patient', 'doctor', 'clinic'])
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('dashboard.index', compact('stats', 'recentAppointments', 'recentPatients', 'todayAppointments'));
    }
}
