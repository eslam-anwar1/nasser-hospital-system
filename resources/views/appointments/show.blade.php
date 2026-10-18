@extends('layouts.app')

@section('title', 'تفاصيل الموعد الطبي: ' . $appointment->appointment_number)
@section('page_title', 'تفاصيل حجز الموعد الطبي - مجمع ناصر الطبي')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
        <div class="flex items-center justify-between pb-6 border-b border-slate-100">
            <div>
                <div class="flex items-center gap-3">
                    <h2 class="text-lg font-black text-slate-800">بيانات بطاقة الحجز الطبي</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $appointment->status_badge_class }}">
                        {{ $appointment->status_arabic }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1">رقم الحجز: <span class="font-mono font-bold text-hospital-700">{{ $appointment->appointment_number }}</span></p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('appointments.edit', $appointment) }}" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                    تعديل
                </a>
                <a href="{{ route('appointments.index') }}" class="px-3.5 py-2 bg-slate-50 hover:bg-slate-100 text-slate-500 rounded-xl text-xs font-bold transition">
                    العودة للقائمة
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-6 border-b border-slate-100">
            <!-- Patient Info Box -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-hospital-700 block mb-2">بيانات المريض</span>
                <h4 class="text-sm font-bold text-slate-800">{{ $appointment->patient->full_name }}</h4>
                <div class="mt-2 space-y-1 text-xs text-slate-600">
                    <div>رقم الملف الطبي: <span class="font-bold text-hospital-800">{{ $appointment->patient->file_number }}</span></div>
                    <div>الهوية: <span class="font-mono">{{ $appointment->patient->national_id }}</span></div>
                    <div>الهاتف: <span class="font-mono" dir="ltr">{{ $appointment->patient->phone }}</span></div>
                    <div>الجنس والعمر: {{ $appointment->patient->gender_arabic }} ({{ $appointment->patient->age }} سنة)</div>
                </div>
                <div class="mt-3">
                    <a href="{{ route('patients.show', $appointment->patient) }}" class="text-xs font-bold text-hospital-600 hover:underline">
                        عرض الملف الطبي الكامل للمريض <i class="fa-solid fa-arrow-left mr-1"></i>
                    </a>
                </div>
            </div>

            <!-- Doctor & Clinic Info Box -->
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-blue-700 block mb-2">جهة المعاينة الطبية</span>
                <h4 class="text-sm font-bold text-slate-800">{{ $appointment->doctor->name }}</h4>
                <div class="mt-2 space-y-1 text-xs text-slate-600">
                    <div>التخصص: <span class="font-semibold">{{ $appointment->doctor->specialization ?? 'طبيب عام' }}</span></div>
                    <div>القسم: <span class="font-semibold">{{ $appointment->clinic->name_ar ?? 'العيادة العامة' }}</span></div>
                    <div>موقع العيادة: {{ $appointment->clinic->location ?? 'المبنى الرئيسي' }}</div>
                    <div>توقيت الحجز: <span class="font-bold text-slate-800" dir="ltr">{{ $appointment->appointment_date->format('Y-m-d H:i') }}</span></div>
                </div>
            </div>
        </div>

        <div class="pt-4">
            <span class="text-xs font-bold text-slate-700 block mb-1">ملاحظات وسبب الحجز:</span>
            <p class="text-xs text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-200 leading-relaxed">
                {{ $appointment->notes ?: 'لا توجد ملاحظات إضافية مسجلة.' }}
            </p>
        </div>

        <!-- Consultation status / action -->
        <div class="mt-6 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-slate-700">تحديث حالة الموعد:</span>
                <form action="{{ route('appointments.status', $appointment) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    <select name="status" onchange="this.form.submit()" class="text-xs font-bold rounded-xl border px-3 py-1.5 {{ $appointment->status_badge_class }}">
                        <option value="Pending" {{ $appointment->status == 'Pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="Confirmed" {{ $appointment->status == 'Confirmed' ? 'selected' : '' }}>مؤكد</option>
                        <option value="Completed" {{ $appointment->status == 'Completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="Cancelled" {{ $appointment->status == 'Cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </form>
            </div>

            @if($appointment->status !== 'Completed')
            <a href="{{ route('medical-records.create', ['appointment_id' => $appointment->id]) }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md transition flex items-center gap-2">
                <i class="fa-solid fa-stethoscope"></i>
                <span>بدء الكشف الطبي وكتابة التقرير</span>
            </a>
            @else
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
                <i class="fa-solid fa-circle-check"></i> تم إتمام الكشف الطبي وإصدار التقرير
            </span>
            @endif
        </div>
    </div>

</div>
@endsection
