@extends('layouts.app')

@section('title', 'الملف الطبي: ' . $patient->full_name)
@section('page_title', 'الملف الطبي للمريض - مجمع ناصر الطبي')

@section('content')
<div class="space-y-6">

    <!-- Top Patient Header Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-4 sm:p-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-teal-50 border border-teal-200 text-hospital-700 flex items-center justify-center text-xl sm:text-2xl shadow-inner shrink-0">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                        <h2 class="text-lg sm:text-xl font-black text-slate-800">{{ $patient->full_name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-100 text-teal-800 border border-teal-200">
                            {{ $patient->file_number }}
                        </span>
                        @if($patient->blood_group)
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">
                            {{ $patient->blood_group }}
                        </span>
                        @endif
                    </div>
                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 mt-2 text-xs text-slate-500 font-medium">
                        <span><i class="fa-solid fa-id-card ml-1 text-slate-400"></i> الهوية: {{ $patient->national_id }}</span>
                        <span><i class="fa-solid fa-phone ml-1 text-slate-400"></i> الهاتف: <span dir="ltr">{{ $patient->phone }}</span></span>
                        <span><i class="fa-solid fa-venus-mars ml-1 text-slate-400"></i> الجنس: {{ $patient->gender_arabic }}</span>
                        <span><i class="fa-solid fa-cake-candles ml-1 text-slate-400"></i> العمر: {{ $patient->age }} سنة ({{ $patient->date_of_birth->format('Y-m-d') }})</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 w-full lg:w-auto">
                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" class="flex-1 sm:flex-initial px-3 sm:px-4 py-2 bg-hospital-600 text-white rounded-xl text-xs font-bold shadow-md hover:bg-hospital-700 transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <span>حجز موعد</span>
                </a>
                <a href="{{ route('medical-records.create', ['patient_id' => $patient->id]) }}" class="flex-1 sm:flex-initial px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-bold shadow-md hover:bg-blue-700 transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-notes-medical"></i>
                    <span>إضافة كشف</span>
                </a>
                <a href="{{ route('patients.edit', $patient) }}" class="px-3 sm:px-4 py-2 bg-slate-100 text-slate-700 rounded-xl text-xs font-bold hover:bg-slate-200 transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-pen"></i>
                    <span>تعديل</span>
                </a>
            </div>
        </div>

        <!-- Emergency and Allergy Warnings -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4 mt-6 pt-6 border-t border-slate-100">
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                <span class="text-[11px] font-bold text-slate-500 block mb-1">
                    <i class="fa-solid fa-location-dot text-slate-400 ml-1"></i> العنوان السكني
                </span>
                <p class="text-xs font-semibold text-slate-700">{{ $patient->address ?? 'غير مسجل' }}</p>
            </div>

            <div class="p-3.5 rounded-xl bg-amber-50/70 border border-amber-200">
                <span class="text-[11px] font-bold text-amber-800 block mb-1">
                    <i class="fa-solid fa-shield-virus text-amber-600 ml-1"></i> الحساسيات الدوائية والغذائية
                </span>
                <p class="text-xs font-bold text-amber-900">{{ $patient->known_allergies ?: 'لا توجد حساسيات مسجلة' }}</p>
            </div>

            <div class="p-3.5 rounded-xl bg-rose-50/70 border border-rose-200 sm:col-span-2 md:col-span-1">
                <span class="text-[11px] font-bold text-rose-800 block mb-1">
                    <i class="fa-solid fa-heart-pulse text-rose-600 ml-1"></i> الأمراض المزمنة
                </span>
                <p class="text-xs font-bold text-rose-900">{{ $patient->chronic_diseases ?: 'سليم من الأمراض المزمنة' }}</p>
            </div>
        </div>
    </div>

    <!-- History Tabs: Medical Records & Appointments -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Medical Records List -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-hospital-600"></div>
                    <h3 class="text-sm font-bold text-slate-800">سجل الاستشارات والكشوفات الطبية</h3>
                </div>
                <span class="text-xs font-bold text-hospital-700 bg-hospital-50 px-2.5 py-0.5 rounded-full border border-hospital-200">
                    {{ $patient->medicalRecords->count() }} زيارة
                </span>
            </div>

            <div class="p-3 sm:p-4 space-y-4 max-h-[500px] overflow-y-auto">
                @forelse($patient->medicalRecords as $record)
                <div class="p-3.5 sm:p-4 rounded-xl border border-slate-200 hover:border-hospital-300 transition bg-slate-50/50">
                    <div class="flex flex-wrap items-center justify-between gap-1 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-hospital-800">{{ $record->record_number }}</span>
                            <span class="text-[10px] text-slate-400">&bull;</span>
                            <span class="text-xs font-semibold text-slate-600">{{ $record->doctor->name }}</span>
                        </div>
                        <span class="text-[11px] text-slate-500 font-mono" dir="ltr">{{ $record->visit_date->format('Y-m-d') }}</span>
                    </div>

                    <div class="space-y-1.5 text-xs">
                        <div>
                            <span class="font-bold text-slate-700">الشكوى:</span>
                            <span class="text-slate-600">{{ $record->chief_complaint }}</span>
                        </div>
                        <div>
                            <span class="font-bold text-teal-800">التشخيص:</span>
                            <span class="text-slate-800 font-semibold">{{ $record->diagnosis }}</span>
                        </div>
                        @if($record->prescription)
                        <div class="bg-white p-2.5 rounded-lg border border-slate-200 text-[11px]">
                            <span class="font-bold text-slate-700 block mb-1"><i class="fa-solid fa-prescription ml-1 text-hospital-600"></i> العلاج الموصوف:</span>
                            <pre class="font-sans whitespace-pre-wrap text-slate-600">{{ $record->prescription }}</pre>
                        </div>
                        @endif
                    </div>

                    <div class="mt-3 pt-2 border-t border-slate-200/60 flex flex-wrap items-center justify-between gap-2 text-[11px] text-slate-500">
                        <span>العيادة: {{ $record->clinic->name_ar ?? 'العيادة العامة' }}</span>
                        <a href="{{ route('medical-records.show', $record) }}" class="text-hospital-600 font-bold hover:underline">
                            عرض التفاصيل والطباعة <i class="fa-solid fa-arrow-left mr-1"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="py-10 text-center text-xs text-slate-400">
                    <i class="fa-solid fa-notes-medical text-3xl mb-2 text-slate-300 block"></i>
                    لا توجد كشوفات أو استشارات طبية سابقة لهذا المريض
                </div>
                @endforelse
            </div>
        </div>

        <!-- Appointments List -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-blue-600"></div>
                    <h3 class="text-sm font-bold text-slate-800">مواعيد الحجوزات والمراجعات</h3>
                </div>
                <span class="text-xs font-bold text-blue-700 bg-blue-50 px-2.5 py-0.5 rounded-full border border-blue-200">
                    {{ $patient->appointments->count() }} موعد
                </span>
            </div>

            <div class="p-3 sm:p-4 space-y-3 max-h-[500px] overflow-y-auto">
                @forelse($patient->appointments as $apt)
                <div class="p-3 sm:p-3.5 rounded-xl border border-slate-200 hover:border-slate-300 transition flex flex-col sm:flex-row sm:items-center justify-between gap-2 bg-white">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-800">{{ $apt->appointment_number }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $apt->status_badge_class }}">
                                {{ $apt->status_arabic }}
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-500 mt-1">
                            الطبيب: <span class="font-semibold text-slate-700">{{ $apt->doctor->name }}</span> &bull; 
                            {{ $apt->clinic->name_ar ?? 'العيادة' }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end sm:text-left gap-3 pt-1 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                        <div class="text-xs font-bold text-slate-700 font-mono" dir="ltr">
                            {{ $apt->appointment_date->format('Y-m-d H:i') }}
                        </div>
                        <a href="{{ route('appointments.show', $apt) }}" class="text-[11px] text-hospital-600 font-bold hover:underline">
                            عرض الموعد
                        </a>
                    </div>
                </div>
                @empty
                <div class="py-10 text-center text-xs text-slate-400">
                    <i class="fa-solid fa-calendar-xmark text-3xl mb-2 text-slate-300 block"></i>
                    لا توجد مواعيد سابقة أو قادمة
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
