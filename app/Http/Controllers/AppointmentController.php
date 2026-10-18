<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Clinic;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor', 'clinic']);

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->input('doctor_id'));
        }

        if ($request->filled('clinic_id')) {
            $query->where('clinic_id', $request->input('clinic_id'));
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->input('date'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('appointment_number', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($p) use ($search) {
                      $p->where('full_name', 'like', "%{$search}%")
                        ->orWhere('file_number', 'like', "%{$search}%");
                  });
            });
        }

        $appointments = $query->orderBy('appointment_date', 'desc')->paginate(10)->withQueryString();

        $doctors = User::where('role', 'Doctor')->where('is_active', true)->get();
        $clinics = Clinic::where('is_active', true)->get();

        return view('appointments.index', compact('appointments', 'doctors', 'clinics'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('full_name')->get();
        $doctors = User::where('role', 'Doctor')->where('is_active', true)->get();
        $clinics = Clinic::where('is_active', true)->get();

        $selectedPatientId = $request->input('patient_id');

        // Generate next appointment number
        $lastAppointment = Appointment::orderBy('id', 'desc')->first();
        $nextNum = $lastAppointment ? ($lastAppointment->id + 1) : 1;
        $suggestedNumber = 'APT-' . date('Y') . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        return view('appointments.create', compact('patients', 'doctors', 'clinics', 'selectedPatientId', 'suggestedNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'appointment_number' => ['required', 'string', 'unique:appointments,appointment_number'],
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'clinic_id' => ['nullable', 'exists:clinics,id'],
            'appointment_date' => ['required', 'date'],
            'status' => ['required', 'in:Pending,Confirmed,Completed,Cancelled'],
            'priority' => ['required', 'in:Normal,Urgent,Emergency'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'appointment_number.required' => 'رقم الحجز مطلوب.',
            'appointment_number.unique' => 'رقم الحجز مسجل مسبقاً.',
            'patient_id.required' => 'يرجى اختيار المريض.',
            'doctor_id.required' => 'يرجى تعيين الطبيب المعالج.',
            'appointment_date.required' => 'يرجى تحديد موعد وتاريخ الكشف.',
        ]);

        $appointment = Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'تم حجز الموعد الطبي بنجاح للمريض.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'clinic', 'medicalRecord']);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('full_name')->get();
        $doctors = User::where('role', 'Doctor')->where('is_active', true)->get();
        $clinics = Clinic::where('is_active', true)->get();

        return view('appointments.edit', compact('appointment', 'patients', 'doctors', 'clinics'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'clinic_id' => ['nullable', 'exists:clinics,id'],
            'appointment_date' => ['required', 'date'],
            'status' => ['required', 'in:Pending,Confirmed,Completed,Cancelled'],
            'priority' => ['required', 'in:Normal,Urgent,Emergency'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'patient_id.required' => 'يرجى اختيار المريض.',
            'doctor_id.required' => 'يرجى تعيين الطبيب المعالج.',
            'appointment_date.required' => 'يرجى تحديد موعد وتاريخ الكشف.',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'تم تحديث بيانات الموعد بنجاح.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => ['required', 'in:Pending,Confirmed,Completed,Cancelled'],
        ]);

        $appointment->update(['status' => $request->input('status')]);

        return back()->with('success', 'تم تغيير حالة الموعد الطبي بنجاح إلى: ' . $appointment->status_arabic);
    }

    public function destroy(Appointment $appointment)
    {
        $num = $appointment->appointment_number;
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', "تم إلغاء وحذف الموعد ({$num}) بنجاح.");
    }
}
