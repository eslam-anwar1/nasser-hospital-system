@extends('layouts.app')

@section('title', 'جدول المواعيد الطبية')
@section('page_title', 'جدولة وإدارة المواعيد الطبية - مجمع ناصر الطبي')

@section('content')
<div class="space-y-6">

    <!-- Filters and Action -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4">
        <!-- Filter Form -->
        <form method="GET" action="{{ route('appointments.index') }}" class="w-full lg:flex-1 flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-2.5 sm:gap-3">
            <div class="relative w-full sm:w-56 md:w-60">
                <i class="fa-solid fa-magnifying-glass absolute right-3.5 top-3 text-slate-400 text-xs"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="رقم الحجز، اسم المريض..." 
                       class="w-full pl-4 pr-9 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-hospital-500">
            </div>

            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full sm:w-auto">
                <select name="status" class="w-full sm:w-auto py-2 px-3 rounded-xl border border-slate-200 text-xs text-slate-700">
                    <option value="">جميع الحالات</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="Confirmed" {{ request('status') == 'Confirmed' ? 'selected' : '' }}>مؤكد</option>
                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>

                <select name="doctor_id" class="w-full sm:w-auto py-2 px-3 rounded-xl border border-slate-200 text-xs text-slate-700">
                    <option value="">جميع الأطباء</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>{{ $doc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <input type="date" name="date" value="{{ request('date') }}" class="w-full sm:w-auto py-2 px-3 rounded-xl border border-slate-200 text-xs text-slate-700">

                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition shrink-0">
                    تصفية
                </button>

                @if(request()->hasAny(['search', 'status', 'doctor_id', 'date']))
                    <a href="{{ route('appointments.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-bold shrink-0">إلغاء</a>
                @endif
            </div>
        </form>

        <a href="{{ route('appointments.create') }}" class="w-full lg:w-auto px-5 py-2.5 bg-hospital-600 text-white rounded-xl text-xs font-bold shadow-md hover:bg-hospital-700 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-calendar-plus"></i>
            <span>حجز موعد جديد</span>
        </a>
    </div>

    <!-- Appointments Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">قائمة المواعيد والكشوفات ({{ $appointments->total() }})</h3>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-right text-xs min-w-[750px]">
                <thead class="bg-slate-50/80 text-slate-500 font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-3 sm:px-4">رقم الموعد</th>
                        <th class="py-3.5 px-3 sm:px-4">المريض</th>
                        <th class="py-3.5 px-3 sm:px-4">الطبيب المعالج</th>
                        <th class="py-3.5 px-3 sm:px-4">القسم / العيادة</th>
                        <th class="py-3.5 px-3 sm:px-4">تاريخ وساعة الموعد</th>
                        <th class="py-3.5 px-3 sm:px-4">الأولوية</th>
                        <th class="py-3.5 px-3 sm:px-4">حالة الحجز</th>
                        <th class="py-3.5 px-3 sm:px-4 text-center">إجراءات سريعة</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($appointments as $apt)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-700 whitespace-nowrap">
                            <span class="bg-slate-100 border border-slate-200 px-2 py-0.5 rounded font-mono">{{ $apt->appointment_number }}</span>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 whitespace-nowrap">
                            <a href="{{ route('patients.show', $apt->patient) }}" class="font-bold text-slate-800 hover:text-hospital-600 block">
                                {{ $apt->patient->full_name }}
                            </a>
                            <span class="text-[10px] text-slate-400">ملف: {{ $apt->patient->file_number }}</span>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-700 whitespace-nowrap">
                            {{ $apt->doctor->name }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-slate-600 whitespace-nowrap">
                            {{ $apt->clinic->name_ar ?? 'العيادة العامة' }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-slate-700 font-semibold whitespace-nowrap" dir="ltr">
                            {{ $apt->appointment_date->format('Y-m-d H:i') }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 whitespace-nowrap">
                            @if($apt->priority === 'Emergency')
                                <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 font-bold text-[10px] animate-pulse">طوارئ</span>
                            @elseif($apt->priority === 'Urgent')
                                <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-bold text-[10px]">مستعجل</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 font-medium text-[10px]">عادي</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 whitespace-nowrap">
                            <!-- Quick status change drop -->
                            <form action="{{ route('appointments.status', $apt) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="text-[11px] font-bold rounded-lg border px-2 py-1 {{ $apt->status_badge_class }}">
                                    <option value="Pending" {{ $apt->status == 'Pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                    <option value="Confirmed" {{ $apt->status == 'Confirmed' ? 'selected' : '' }}>مؤكد</option>
                                    <option value="Completed" {{ $apt->status == 'Completed' ? 'selected' : '' }}>مكتمل</option>
                                    <option value="Cancelled" {{ $apt->status == 'Cancelled' ? 'selected' : '' }}>ملغي</option>
                                </select>
                            </form>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                @if($apt->status !== 'Completed')
                                <a href="{{ route('medical-records.create', ['appointment_id' => $apt->id]) }}" title="بدء الكشف الطبي وتوثيق السجل" class="p-1.5 rounded-lg text-emerald-600 hover:bg-emerald-50 transition">
                                    <i class="fa-solid fa-stethoscope"></i>
                                </a>
                                @endif
                                <a href="{{ route('appointments.show', $apt) }}" title="عرض الموعد" class="p-1.5 rounded-lg text-teal-600 hover:bg-teal-50 transition">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('appointments.edit', $apt) }}" title="تعديل الموعد" class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('appointments.destroy', $apt) }}" method="POST" onsubmit="return confirm('هل تريد إلغاء وحذف هذا الموعد الطبي؟');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="حذف" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-calendar-xmark text-3xl mb-2 text-slate-300 block"></i>
                            لا توجد مواعيد طبية مطابقة لخيارات التصفية
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($appointments->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $appointments->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
