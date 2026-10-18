<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('file_number', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('blood_group')) {
            $query->where('blood_group', $request->input('blood_group'));
        }

        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        // Generate next file number
        $lastPatient = Patient::orderBy('id', 'desc')->first();
        $nextNum = $lastPatient ? ($lastPatient->id + 1001) : 1001;
        $suggestedFileNumber = 'NAS-P-' . $nextNum;

        return view('patients.create', compact('suggestedFileNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'file_number' => ['required', 'string', 'unique:patients,file_number'],
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['required', 'string', 'max:50', 'unique:patients,national_id'],
            'phone' => ['required', 'string', 'max:30'],
            'gender' => ['required', 'in:ذكر,أنثى'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'known_allergies' => ['nullable', 'string'],
            'chronic_diseases' => ['nullable', 'string'],
        ], [
            'file_number.required' => 'رقم الملف الطبي مطلوب.',
            'file_number.unique' => 'رقم الملف الطبي مسجل مسبقاً لمريض آخر.',
            'full_name.required' => 'اسم المريض بالكامل مطلوب.',
            'national_id.required' => 'رقم الهوية الوطنية / الإقامة مطلوب.',
            'national_id.unique' => 'رقم الهوية مسجل مسبقاً.',
            'phone.required' => 'رقم هاتف المريض مطلوب.',
            'gender.required' => 'يرجى تحديد جنس المريض.',
            'date_of_birth.required' => 'تاريخ الميلاد مطلوب.',
            'date_of_birth.before' => 'تاريخ الميلاد يجب أن يكون تاريخاً سابقاً لليوم.',
        ]);

        $patient = Patient::create($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'تم فتح الملف الطبي للمريض بنجاح.');
    }

    public function show(Patient $patient)
    {
        $patient->load(['appointments.doctor', 'appointments.clinic', 'medicalRecords.doctor', 'medicalRecords.clinic']);

        return view('patients.show', compact('patient'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'file_number' => ['required', 'string', 'unique:patients,file_number,' . $patient->id],
            'full_name' => ['required', 'string', 'max:255'],
            'national_id' => ['required', 'string', 'max:50', 'unique:patients,national_id,' . $patient->id],
            'phone' => ['required', 'string', 'max:30'],
            'gender' => ['required', 'in:ذكر,أنثى'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'blood_group' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string', 'max:500'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'known_allergies' => ['nullable', 'string'],
            'chronic_diseases' => ['nullable', 'string'],
        ], [
            'file_number.required' => 'رقم الملف الطبي مطلوب.',
            'file_number.unique' => 'رقم الملف الطبي مسجل مسبقاً لمريض آخر.',
            'full_name.required' => 'اسم المريض بالكامل مطلوب.',
            'national_id.required' => 'رقم الهوية الوطنية / الإقامة مطلوب.',
            'national_id.unique' => 'رقم الهوية مسجل مسبقاً.',
            'phone.required' => 'رقم هاتف المريض مطلوب.',
            'gender.required' => 'يرجى تحديد جنس المريض.',
            'date_of_birth.required' => 'تاريخ الميلاد مطلوب.',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)
            ->with('success', 'تم تحديث بيانات الملف الطبي للمريض بنجاح.');
    }

    public function destroy(Patient $patient)
    {
        $patientName = $patient->full_name;
        $patient->delete();

        return redirect()->route('patients.index')
            ->with('success', "تم حذف ملف المريض ({$patientName}) من النظام الطبي.");
    }
}
