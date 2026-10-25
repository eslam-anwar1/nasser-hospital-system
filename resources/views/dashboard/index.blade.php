@extends('layouts.app')

@section('title', 'لوحة التحكم والمؤشرات الطبية')
@section('page_title', 'لوحة التحكم والعمليات اليومية - مجمع ناصر الطبي')

@section('content')
<div class="space-y-6">

    <!-- Hospital Welcome Banner -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-l from-hospital-800 via-hospital-700 to-teal-900 text-white p-5 sm:p-6 shadow-md border border-hospital-600/30">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-5">
            <div>
                <span class="inline-block px-3 py-1 rounded-full bg-white/15 text-teal-200 text-xs font-bold mb-2 backdrop-blur-sm">
                    <i class="fa-solid fa-hospital-user ml-1"></i> مجمع ناصر الطبي الحكومي التخصصي
                </span>
                <h1 class="text-xl sm:text-2xl font-black">أهلاً بك، {{ auth()->user()->name }}</h1>
                <p class="text-teal-100 text-xs sm:text-sm mt-1 max-w-xl leading-relaxed">
                    مرحباً بك في المنظومة الرقمية لمجمع ناصر الطبي. يمكنك متابعة حجوزات المراجعين، إحصائيات العيادات، وسجلات الأطباء اليومية بكل دقة وسلاسة.
                </p>
            </div>
            <div class="flex flex-col sm:flex-row sm:items-center gap-2.5 sm:gap-3 w-full sm:w-auto">
                <a href="{{ route('patients.create') }}" class="w-full sm:w-auto px-4 py-2.5 bg-white text-hospital-800 rounded-xl font-bold text-xs shadow-md hover:bg-teal-50 transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-user-plus text-sm"></i>
                    <span>فتح ملف مريض جديد</span>
                </a>
                <a href="{{ route('appointments.create') }}" class="w-full sm:w-auto px-4 py-2.5 bg-teal-500/30 hover:bg-teal-500/40 text-white border border-white/20 rounded-xl font-bold text-xs shadow-md transition flex items-center justify-center gap-2">
                    <i class="fa-solid fa-calendar-plus text-sm"></i>
                    <span>جدولة كشف طبي</span>
                </a>
            </div>
        </div>
        <!-- Hospital Decorative BG Icon -->
        <i class="fa-solid fa-heart-pulse absolute -left-6 -bottom-8 text-8xl sm:text-9xl text-white/5 pointer-events-none"></i>
    </div>

    <!-- Live Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <!-- Stat: Patients -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-slate-500 mb-1">إجمالي المرضى المسجلين</p>
                <h3 class="text-xl sm:text-2xl font-black text-slate-800">{{ $stats['total_patients'] }}</h3>
                <span class="text-[11px] text-teal-600 font-semibold mt-1 inline-flex items-center gap-1">
                    <i class="fa-solid fa-circle-check"></i> ملفات طبية نشطة
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-hospital-user"></i>
            </div>
        </div>

        <!-- Stat: Today Appointments -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-slate-500 mb-1">مواعيد كشوفات اليوم</p>
                <h3 class="text-xl sm:text-2xl font-black text-slate-800">{{ $stats['today_appointments'] }}</h3>
                <span class="text-[11px] text-blue-600 font-semibold mt-1 inline-flex items-center gap-1">
                    <i class="fa-solid fa-clock"></i> مجدولة لليوم الحالي
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
        </div>

        <!-- Stat: Pending Appointments -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-slate-500 mb-1">في انتظار المعاينة</p>
                <h3 class="text-xl sm:text-2xl font-black text-amber-600">{{ $stats['pending_appointments'] }}</h3>
                <span class="text-[11px] text-amber-700 font-semibold mt-1 inline-flex items-center gap-1">
                    <i class="fa-solid fa-hourglass-half"></i> قيد الانتظار بالعيادة
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-user-clock"></i>
            </div>
        </div>

        <!-- Stat: Active Doctors -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between hover:shadow-md transition">
            <div>
                <p class="text-xs font-bold text-slate-500 mb-1">الأطباء الاستشاريون المناوبون</p>
                <h3 class="text-xl sm:text-2xl font-black text-slate-800">{{ $stats['active_doctors'] }}</h3>
                <span class="text-[11px] text-purple-600 font-semibold mt-1 inline-flex items-center gap-1">
                    <i class="fa-solid fa-stethoscope"></i> جاهزون للاستشارات
                </span>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-user-doctor"></i>
            </div>
        </div>
    </div>

    <!-- Main Grid: Appointments & Patients -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Recent Appointments (2 Columns) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div class="flex items-center gap-2">
                    <div class="w-2.5 h-2.5 rounded-full bg-hospital-600"></div>
                    <h3 class="text-sm font-bold text-slate-800">أحدث المواعيد الطبية المجدولة</h3>
                </div>
                <a href="{{ route('appointments.index') }}" class="text-xs font-bold text-hospital-600 hover:text-hospital-700">
                    عرض جميع المواعيد <i class="fa-solid fa-arrow-left mr-1"></i>
                </a>
            </div>

            <!-- Responsive Table Wrapper with Horizontal Scroll -->
            <div class="overflow-x-auto w-full">
                <table class="w-full text-right text-xs min-w-[600px]">
                    <thead class="bg-slate-50/80 text-slate-500 font-bold border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-3 sm:px-4">رقم الحجز</th>
                            <th class="py-3 px-3 sm:px-4">المريض</th>
                            <th class="py-3 px-3 sm:px-4">الطبيب المعالج</th>
                            <th class="py-3 px-3 sm:px-4">العيادة</th>
                            <th class="py-3 px-3 sm:px-4">الموعد</th>
                            <th class="py-3 px-3 sm:px-4">الحالة</th>
                            <th class="py-3 px-3 sm:px-4 text-center">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentAppointments as $apt)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-700 whitespace-nowrap">
                                <span class="bg-slate-100 px-2 py-0.5 rounded text-[11px]">{{ $apt->appointment_number }}</span>
                            </td>
                            <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-800 whitespace-nowrap">
                                <a href="{{ route('patients.show', $apt->patient) }}" class="hover:text-hospital-600">
                                    {{ $apt->patient->full_name }}
                                </a>
                            </td>
                            <td class="py-3.5 px-3 sm:px-4 text-slate-600 whitespace-nowrap">
                                <span class="font-medium">{{ $apt->doctor->name }}</span>
                            </td>
                            <td class="py-3.5 px-3 sm:px-4 text-slate-600 whitespace-nowrap">
                                {{ $apt->clinic->name_ar ?? 'العيادة العامة' }}
                            </td>
                            <td class="py-3.5 px-3 sm:px-4 text-slate-600 whitespace-nowrap" dir="ltr">
                                {{ $apt->appointment_date->format('Y-m-d H:i') }}
                            </td>
                            <td class="py-3.5 px-3 sm:px-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold border {{ $apt->status_badge_class }}">
                                    {{ $apt->status_arabic }}
                                </span>
                            </td>
                            <td class="py-3.5 px-3 sm:px-4 text-center whitespace-nowrap">
                                <a href="{{ route('appointments.show', $apt) }}" class="text-hospital-600 hover:text-hospital-800 p-1 inline-block">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                لا توجد مواعيد طبية مسجلة حالياً
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Latest Patients Registered (1 Column) -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm flex flex-col justify-between overflow-hidden">
            <div>
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-600"></div>
                        <h3 class="text-sm font-bold text-slate-800">أحدث المرضى المسجلين</h3>
                    </div>
                    <a href="{{ route('patients.index') }}" class="text-xs font-bold text-hospital-600 hover:text-hospital-700">
                        عرض السجلات <i class="fa-solid fa-arrow-left mr-1"></i>
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse($recentPatients as $pat)
                    <div class="p-3.5 sm:p-4 hover:bg-slate-50/60 transition flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full bg-teal-100/60 text-teal-800 flex items-center justify-center font-bold text-xs shrink-0">
                                <i class="fa-solid fa-user-injured"></i>
                            </div>
                            <div class="min-w-0">
                                <a href="{{ route('patients.show', $pat) }}" class="text-xs font-bold text-slate-800 hover:text-hospital-600 block truncate">
                                    {{ $pat->full_name }}
                                </a>
                                <span class="text-[10px] text-slate-500 block truncate">رقم الملف: {{ $pat->file_number }} &bull; فصيلة: {{ $pat->blood_group ?? '-' }}</span>
                            </div>
                        </div>
                        <a href="{{ route('patients.show', $pat) }}" class="text-slate-400 hover:text-hospital-600 text-xs shrink-0 p-1">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    </div>
                    @empty
                    <div class="p-6 text-center text-xs text-slate-400">
                        لا يوجد مرضى مسجلون حديثاً
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Action Box -->
            <div class="p-4 m-3 sm:m-4 rounded-xl bg-slate-50 border border-slate-200/60">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-slate-700">الأقسام الطبية العاملة</span>
                    <span class="text-xs font-bold text-hospital-700">{{ $stats['active_clinics'] }} عيادات متخصصة</span>
                </div>
                <p class="text-[11px] text-slate-500 leading-relaxed">جميع الأقسام التخصصية في مجمع ناصر جاهزة لاستقبال المرضى وحالات الطوارئ.</p>
            </div>
        </div>

    </div>

</div>
@endsection
