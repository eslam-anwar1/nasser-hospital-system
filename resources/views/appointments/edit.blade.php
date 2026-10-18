@extends('layouts.app')

@section('title', 'تعديل الموعد: ' . $appointment->appointment_number)
@section('page_title', 'تعديل موعد طبي - مجمع ناصر الطبي')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">تعديل بيانات الموعد: {{ $appointment->appointment_number }}</h3>
                    <p class="text-xs text-slate-500">المريض: {{ $appointment->patient->full_name }}</p>
                </div>
            </div>
            <a href="{{ route('appointments.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                <i class="fa-solid fa-arrow-right"></i>
                <span>جدول المواعيد</span>
            </a>
        </div>

        <form action="{{ route('appointments.update', $appointment) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">اختيار المريض <span class="text-rose-500">*</span></label>
                    <select name="patient_id" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ (old('patient_id', $appointment->patient_id) == $patient->id) ? 'selected' : '' }}>
                                {{ $patient->full_name }} (ملف: {{ $patient->file_number }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">الطبيب المعالج / الاستشاري <span class="text-rose-500">*</span></label>
                    <select name="doctor_id" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                        @foreach($doctors as $doc)
                            <option value="{{ $doc->id }}" {{ old('doctor_id', $appointment->doctor_id) == $doc->id ? 'selected' : '' }}>
                                {{ $doc->name }} ({{ $doc->specialization ?? 'طبيب عام' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">القسم أو العيادة الطبية</label>
                    <select name="clinic_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                        <option value="">-- اختر القسم الطبي --</option>
                        @foreach($clinics as $cln)
                            <option value="{{ $cln->id }}" {{ old('clinic_id', $appointment->clinic_id) == $cln->id ? 'selected' : '' }}>
                                {{ $cln->name_ar }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ وتوقيت الموعد <span class="text-rose-500">*</span></label>
                    <input type="datetime-local" name="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d\TH:i')) }}" required 
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">أولوية الكشف</label>
                    <select name="priority" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                        <option value="Normal" {{ old('priority', $appointment->priority) == 'Normal' ? 'selected' : '' }}>عادي (كشف اعتيادي)</option>
                        <option value="Urgent" {{ old('priority', $appointment->priority) == 'Urgent' ? 'selected' : '' }}>مستعجل (حالة عاجلة)</option>
                        <option value="Emergency" {{ old('priority', $appointment->priority) == 'Emergency' ? 'selected' : '' }}>طوارئ (حرجة جداً)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">حالة الحجز</label>
                    <select name="status" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                        <option value="Pending" {{ old('status', $appointment->status) == 'Pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="Confirmed" {{ old('status', $appointment->status) == 'Confirmed' ? 'selected' : '' }}>مؤكد</option>
                        <option value="Completed" {{ old('status', $appointment->status) == 'Completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="Cancelled" {{ old('status', $appointment->status) == 'Cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">ملاحظات وسبب الزيارة</label>
                <textarea name="notes" rows="3" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">{{ old('notes', $appointment->notes) }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('appointments.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>حفظ التعديلات</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
