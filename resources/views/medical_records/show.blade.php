@extends('layouts.app')

@section('title', 'تفاصيل التقرير الطبي: ' . $medicalRecord->record_number)
@section('page_title', 'التقرير الطبي والكشف السريري - مجمع ناصر الطبي')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Report Document Style Box -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-md p-8 print:p-0 print:border-none print:shadow-none" id="printable-record">
        
        <!-- Hospital Official Header -->
        <div class="border-b-2 border-slate-900 pb-6 mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-right">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-hospital-700 text-white flex items-center justify-center text-3xl shadow-md">
                    <i class="fa-solid fa-hospital"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900">مجمع ناصر الطبي الحكومي</h2>
                    <p class="text-xs text-slate-600 font-bold">Nasser Medical Complex &bull; إدارة الشؤون الطبية والاستشفائية</p>
                    <p class="text-[11px] text-teal-700 font-semibold mt-0.5">قسم: {{ $medicalRecord->clinic->name_ar ?? 'العيادات الخارجية التخصصية' }}</p>
                </div>
            </div>

            <div class="text-left font-mono">
                <div class="text-xs text-slate-500 font-bold">تقرير كشف طبي رقم</div>
                <div class="text-base font-black text-hospital-800 tracking-wider">{{ $medicalRecord->record_number }}</div>
                <div class="text-xs text-slate-600 mt-1">تاريخ المعاينة: {{ $medicalRecord->visit_date->format('Y-m-d') }}</div>
            </div>
        </div>

        <!-- Patient Demographics Strip -->
        <div class="bg-slate-50 rounded-xl p-4 border border-slate-200 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs mb-6">
            <div>
                <span class="text-slate-400 block text-[10px]">اسم المريض:</span>
                <span class="font-bold text-slate-800">{{ $medicalRecord->patient->full_name }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px]">رقم الملف الطبي:</span>
                <span class="font-bold text-hospital-800">{{ $medicalRecord->patient->file_number }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px]">الهوية / العمر:</span>
                <span class="font-bold text-slate-800">{{ $medicalRecord->patient->national_id }} ({{ $medicalRecord->patient->age }} سنة)</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px]">فصيلة الدم / الجنس:</span>
                <span class="font-bold text-rose-700">{{ $medicalRecord->patient->blood_group ?? '-' }} / {{ $medicalRecord->patient->gender_arabic }}</span>
            </div>
        </div>

        <!-- Vital Signs Strip -->
        <div class="grid grid-cols-4 gap-3 mb-6">
            <div class="p-3 bg-teal-50/50 rounded-xl border border-teal-100 text-center">
                <span class="text-[10px] text-slate-500 block">ضغط الدم</span>
                <span class="text-sm font-black text-teal-800 font-mono">{{ $medicalRecord->blood_pressure ?: 'غير مقاس' }}</span>
            </div>
            <div class="p-3 bg-blue-50/50 rounded-xl border border-blue-100 text-center">
                <span class="text-[10px] text-slate-500 block">نبض القلب</span>
                <span class="text-sm font-black text-blue-800 font-mono">{{ $medicalRecord->pulse_rate ? $medicalRecord->pulse_rate . ' bpm' : '-' }}</span>
            </div>
            <div class="p-3 bg-amber-50/50 rounded-xl border border-amber-100 text-center">
                <span class="text-[10px] text-slate-500 block">درجة الحرارة</span>
                <span class="text-sm font-black text-amber-800 font-mono">{{ $medicalRecord->temperature ? $medicalRecord->temperature . ' °C' : '-' }}</span>
            </div>
            <div class="p-3 bg-purple-50/50 rounded-xl border border-purple-100 text-center">
                <span class="text-[10px] text-slate-500 block">الوزن</span>
                <span class="text-sm font-black text-purple-800 font-mono">{{ $medicalRecord->weight ? $medicalRecord->weight . ' kg' : '-' }}</span>
            </div>
        </div>

        <!-- Clinical Details -->
        <div class="space-y-6 text-xs text-slate-800">
            <!-- Complaint -->
            <div>
                <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-1.5 text-sm">
                    <i class="fa-solid fa-comment-medical text-hospital-600"></i> الشكوى الرئيسية والأعراض:
                </h4>
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 leading-relaxed font-medium">
                    {{ $medicalRecord->chief_complaint }}
                </div>
            </div>

            <!-- Diagnosis -->
            <div>
                <h4 class="font-bold text-teal-900 mb-1 flex items-center gap-1.5 text-sm">
                    <i class="fa-solid fa-file-medical text-teal-600"></i> التشخيص الطبي السريري:
                </h4>
                <div class="p-4 rounded-xl bg-teal-50/70 border border-teal-200 text-teal-950 leading-relaxed font-bold">
                    {{ $medicalRecord->diagnosis }}
                </div>
            </div>

            <!-- Prescription -->
            @if($medicalRecord->prescription)
            <div>
                <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-1.5 text-sm">
                    <i class="fa-solid fa-prescription text-hospital-600"></i> الوصفة الطبية والعلاج الموصوف:
                </h4>
                <div class="p-4 rounded-xl bg-white border-2 border-dashed border-hospital-300 font-mono text-slate-700 leading-relaxed whitespace-pre-line text-xs">
                    {{ $medicalRecord->prescription }}
                </div>
            </div>
            @endif

            <!-- Doctor Notes -->
            @if($medicalRecord->doctor_notes)
            <div>
                <h4 class="font-bold text-slate-900 mb-1 flex items-center gap-1.5 text-sm">
                    <i class="fa-solid fa-clipboard-list text-hospital-600"></i> توصيات الطبيب وإرشادات المراجعة:
                </h4>
                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 leading-relaxed text-slate-600">
                    {{ $medicalRecord->doctor_notes }}
                </div>
            </div>
            @endif
        </div>

        <!-- Doctor Signature Area -->
        <div class="mt-8 pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-600">
            <div>
                <span class="block text-slate-400">الطبيب المشرف والمعالج:</span>
                <span class="font-bold text-slate-800 text-sm">{{ $medicalRecord->doctor->name }}</span>
                <span class="block text-[11px] text-teal-700 font-medium">{{ $medicalRecord->doctor->specialization }}</span>
            </div>

            <div class="text-center mt-4 sm:mt-0">
                <div class="w-32 border-b border-dashed border-slate-400 pb-6 mb-1 text-[11px] text-slate-400">
                    ختم وتوقيع الطبيب
                </div>
                <span class="text-[10px] text-slate-400">مجمع ناصر الطبي</span>
            </div>
        </div>

    </div>

    <!-- Actions -->
    <div class="flex items-center justify-between print:hidden">
        <a href="{{ route('medical-records.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
            <i class="fa-solid fa-arrow-right ml-1"></i> العودة للسجلات الطبية
        </a>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-print"></i>
                <span>طباعة التقرير الطبي</span>
            </button>
            <a href="{{ route('medical-records.edit', $medicalRecord) }}" class="px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>تعديل التقرير</span>
            </a>
        </div>
    </div>

</div>
@endsection
