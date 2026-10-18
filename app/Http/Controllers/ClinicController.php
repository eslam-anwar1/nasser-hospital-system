<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClinicController extends Controller
{
    public function index()
    {
        $clinics = Clinic::withCount(['appointments', 'medicalRecords'])->get();

        return view('clinics.index', compact('clinics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'unique:clinics,code'],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'consultation_fee' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ], [
            'code.required' => 'رمز القسم الطبي مطلوب.',
            'code.unique' => 'رمز القسم مستخدم مسبقاً.',
            'name_ar.required' => 'اسم القسم أو العيادة بالعربية مطلوب.',
            'consultation_fee.required' => 'رسوم الكشف مطلوبة.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Clinic::create($validated);

        return redirect()->route('clinics.index')
            ->with('success', 'تم إضافة القسم / العيادة الطبية بنجاح.');
    }

    public function update(Request $request, Clinic $clinic)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', Rule::unique('clinics')->ignore($clinic->id)],
            'name_ar' => ['required', 'string', 'max:255'],
            'name_en' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'consultation_fee' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ], [
            'name_ar.required' => 'اسم القسم أو العيادة بالعربية مطلوب.',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $clinic->update($validated);

        return redirect()->route('clinics.index')
            ->with('success', 'تم تحديث بيانات العيادة الطبية بنجاح.');
    }

    public function destroy(Clinic $clinic)
    {
        $name = $clinic->name_ar;
        $clinic->delete();

        return redirect()->route('clinics.index')
            ->with('success', "تم حذف عيادة ({$name}) بنجاح.");
    }
}
