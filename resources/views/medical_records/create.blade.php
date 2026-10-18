@extends('layouts.app')

@section('title', 'تسجيل كشف طبي جديد')
@section('page_title', 'كشف طبي وتشخيص استشاري - مجمع ناصر الطبي')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-notes-medical"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">توثيق الكشف والتشخيص الطبي</h3>
                    <p class="text-xs text-slate-500">كتابة الشكوى السريرية، الفحص البدني، العلامات الحيوية، والوصفة العلاجية</p>
                </div>
            </div>
            <a href="{{ route('medical-records.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                <i class="fa-solid fa-arrow-right"></i>
                <span>السجلات الطبية</span>
            </a>
        </div>

        <form action="{{ route('medical-records.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            @if(!empty($appointment))
                <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">
                <div class="p-3.5 rounded-xl bg-teal-50 border border-teal-200 text-teal-800 text-xs flex items-center justify-between">
                    <div>
                        <span class="font-bold">مرتبط بحجز موعد سابق:</span> {{ $appointment->appointment_number }}
                    </div>
                    <span class="font-mono">{{ $appointment->appointment_date->format('Y-m-d H:i') }}</span>
                </div>
            @endif

            <!-- Identification -->
            <div>
                <h4 class="text-xs font-bold text-hospital-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-user-doctor ml-1"></i> أطراف المعاينة الطبية
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم السجل الطبي <span class="text-rose-500">*</span></label>
                        <input type="text" name="record_number" value="{{ old('record_number', $suggestedNumber) }}" required 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono font-bold text-hospital-800 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">المريض <span class="text-rose-500">*</span></label>
                        <select name="patient_id" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                            <option value="">-- اختر المريض --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ (old('patient_id', $selectedPatientId) == $patient->id) ? 'selected' : '' }}>
                                    {{ $patient->full_name }} ({{ $patient->file_number }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الطبيب الفاحص <span class="text-rose-500">*</span></label>
                        <select name="doctor_id" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                            <option value="">-- حدد الطبيب --</option>
                            @foreach($doctors as $doc)
                                <option value="{{ $doc->id }}" {{ (old('doctor_id', auth()->user()->isDoctor() ? auth()->id() : ($appointment->doctor_id ?? null)) == $doc->id) ? 'selected' : '' }}>
                                    {{ $doc->name }} ({{ $doc->specialization ?? 'طبيب عام' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">القسم / العيادة</label>
                        <select name="clinic_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                            <option value="">-- اختر العيادة --</option>
                            @foreach($clinics as $cln)
                                <option value="{{ $cln->id }}" {{ (old('clinic_id', $appointment->clinic_id ?? null) == $cln->id) ? 'selected' : '' }}>
                                    {{ $cln->name_ar }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ الزيارة والكشف <span class="text-rose-500">*</span></label>
                        <input type="date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                    </div>
                </div>
            </div>

            <!-- Vital Signs -->
            <div>
                <h4 class="text-xs font-bold text-hospital-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-heart-pulse ml-1"></i> العلامات الحيوية (Vital Signs)
                </h4>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">ضغط الدم (BP)</label>
                        <input type="text" name="blood_pressure" value="{{ old('blood_pressure') }}" placeholder="مثال: 120/80" 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">معدل النبض (Pulse)</label>
                        <input type="text" name="pulse_rate" value="{{ old('pulse_rate') }}" placeholder="مثال: 72 bpm" 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الحرارة (Temp °C)</label>
                        <input type="text" name="temperature" value="{{ old('temperature') }}" placeholder="مثال: 37.0" 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الوزن (Weight kg)</label>
                        <input type="number" step="0.1" name="weight" value="{{ old('weight') }}" placeholder="مثال: 70" 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500">
                    </div>
                </div>
            </div>

            <!-- Clinical Assessment -->
            <div>
                <h4 class="text-xs font-bold text-hospital-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-stethoscope ml-1"></i> التقييم السريري والتشخيص
                </h4>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الشكوى الرئيسية والأعراض (Chief Complaint) <span class="text-rose-500">*</span></label>
                        <textarea name="chief_complaint" rows="2" required placeholder="وصف الأعراض التي يشكو منها المريض..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">{{ old('chief_complaint') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">التشخيص الطبي السريري (Diagnosis) <span class="text-rose-500">*</span></label>
                        <textarea name="diagnosis" rows="2" required placeholder="التشخيص النهائي أو المبدئي للحالة..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">{{ old('diagnosis') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الوصفة العلاجية والدواء (Prescription & Rx)</label>
                        <textarea name="prescription" rows="4" placeholder="قائمة الأدوية الموصوفة والجرعات اليومية وطريقة الاستخدام..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500 font-mono">{{ old('prescription') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">توصيات الطبيب وموعد المراجعة (Doctor's Notes)</label>
                        <textarea name="doctor_notes" rows="2" placeholder="إرشادات غذائية، حمية، فحوصات مخبرية مطلوبة، أو موعد مراجعة قادم..." class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">{{ old('doctor_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('medical-records.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-hospital-600 hover:bg-hospital-700 text-white text-xs font-bold shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-file-circle-check"></i>
                    <span>حفظ السجل الطبي وإصدار التقرير</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
