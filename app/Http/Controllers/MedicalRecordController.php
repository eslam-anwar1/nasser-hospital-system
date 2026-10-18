<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use App\Models\Clinic;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['patient', 'doctor', 'clinic', 'appointment']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('record_number', 'like', "%{$search}%")
                  ->orWhere('diagnosis', 'like', "%{$search}%")
                  ->orWhere('chief_complaint', 'like', "%{$search}%")
                  ->orWhereHas('patient', function ($p) use ($search) {
                      $p->where('full_name', 'like', "%{$search}%")
                        ->orWhere('file_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->input('doctor_id'));
        }

        $records = $query->latest('visit_date')->paginate(10)->withQueryString();
        $doctors = User::where('role', 'Doctor')->get();

        return view('medical_records.index', compact('records', 'doctors'));
    }

    public function create(Request $request)
    {
        $patients = Patient::orderBy('full_name')->get();
        $doctors = User::where('role', 'Doctor')->where('is_active', true)->get();
        $clinics = Clinic::where('is_active', true)->get();

        $selectedPatientId = $request->input('patient_id');
        $selectedAppointmentId = $request->input('appointment_id');

        $appointment = $selectedAppointmentId ? Appointment::find($selectedAppointmentId) : null;
        if ($appointment) {
            $selectedPatientId = $appointment->patient_id;
        }

        $lastRecord = MedicalRecord::orderBy('id', 'desc')->first();
        $nextNum = $lastRecord ? ($lastRecord->id + 1) : 1;
        $suggestedNumber = 'REC-' . date('Y') . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        return view('medical_records.create', compact('patients', 'doctors', 'clinics', 'selectedPatientId', 'appointment', 'suggestedNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'record_number' => ['required', 'string', 'unique:medical_records,record_number'],
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'clinic_id' => ['nullable', 'exists:clinics,id'],
            'chief_complaint' => ['required', 'string'],
            'diagnosis' => ['required', 'string'],
            'prescription' => ['nullable', 'string'],
            'doctor_notes' => ['nullable', 'string'],
            'blood_pressure' => ['nullable', 'string', 'max:20'],
            'pulse_rate' => ['nullable', 'string', 'max:20'],
            'temperature' => ['nullable', 'string', 'max:20'],
            'weight' => ['nullable', 'numeric'],
            'visit_date' => ['required', 'date'],
        ], [
            'record_number.required' => 'رقم السجل الطبي مطلوب.',
            'patient_id.required' => 'يرجى اختيار المريض.',
            'doctor_id.required' => 'يرجى تحديد الطبيب الفاحص.',
            'chief_complaint.required' => 'الشكوى الرئيسية للمريض مطلوبة.',
            'diagnosis.required' => 'التشخيص الطبي مطلوب.',
            'visit_date.required' => 'تاريخ الكشف والزيارة مطلوب.',
        ]);

        $record = MedicalRecord::create($validated);

        // If linked to an appointment, mark it as completed
        if (!empty($validated['appointment_id'])) {
            Appointment::where('id', $validated['appointment_id'])->update(['status' => 'Completed']);
        }

        return redirect()->route('medical-records.show', $record)
            ->with('success', 'تم حفظ التقرير والكشف الطبي للمريض بنجاح.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['patient', 'doctor', 'clinic', 'appointment']);

        return view('medical_records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $patients = Patient::orderBy('full_name')->get();
        $doctors = User::where('role', 'Doctor')->where('is_active', true)->get();
        $clinics = Clinic::where('is_active', true)->get();

        return view('medical_records.edit', compact('medicalRecord', 'patients', 'doctors', 'clinics'));
    }

    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'clinic_id' => ['nullable', 'exists:clinics,id'],
            'chief_complaint' => ['required', 'string'],
            'diagnosis' => ['required', 'string'],
            'prescription' => ['nullable', 'string'],
            'doctor_notes' => ['nullable', 'string'],
            'blood_pressure' => ['nullable', 'string', 'max:20'],
            'pulse_rate' => ['nullable', 'string', 'max:20'],
            'temperature' => ['nullable', 'string', 'max:20'],
            'weight' => ['nullable', 'numeric'],
            'visit_date' => ['required', 'date'],
        ]);

        $medicalRecord->update($validated);

        return redirect()->route('medical-records.show', $medicalRecord)
            ->with('success', 'تم تحديث السجل الطبي والتشخيص بنجاح.');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $num = $medicalRecord->record_number;
        $medicalRecord->delete();

        return redirect()->route('medical-records.index')
            ->with('success', "تم حذف السجل الطبي ({$num}) بنجاح.");
    }
}
