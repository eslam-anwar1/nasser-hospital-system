@extends('layouts.app')

@section('title', 'السجلات والاستشارات الطبية')
@section('page_title', 'السجلات الطبية وتشخيصات المرضى - مجمع ناصر الطبي')

@section('content')
<div class="space-y-6">

    <!-- Filters and Action -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('medical-records.index') }}" class="w-full md:flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3">
            <div class="relative w-full sm:w-72 md:w-80">
                <i class="fa-solid fa-magnifying-glass absolute right-3.5 top-3 text-slate-400 text-xs"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="رقم السجل، التشخيص، اسم المريض..." 
                       class="w-full pl-4 pr-9 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-hospital-500">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="doctor_id" class="w-full sm:w-auto py-2 px-3 rounded-xl border border-slate-200 text-xs text-slate-700">
                    <option value="">جميع الأطباء الفاحصين</option>
                    @foreach($doctors as $doc)
                        <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>{{ $doc->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition shrink-0">
                    تصفية
                </button>
            </div>
        </form>

        <a href="{{ route('medical-records.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-hospital-600 text-white rounded-xl text-xs font-bold shadow-md hover:bg-hospital-700 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-notes-medical"></i>
            <span>إضافة كشف طبي جديد</span>
        </a>
    </div>

    <!-- Records Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">قائمة الكشوفات والتقارير الطبية ({{ $records->total() }})</h3>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-right text-xs min-w-[750px]">
                <thead class="bg-slate-50/80 text-slate-500 font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-3 sm:px-4">رقم التقرير</th>
                        <th class="py-3.5 px-3 sm:px-4">المريض</th>
                        <th class="py-3.5 px-3 sm:px-4">الطبيب الفاحص</th>
                        <th class="py-3.5 px-3 sm:px-4">القسم / العيادة</th>
                        <th class="py-3.5 px-3 sm:px-4">التشخيص الطبي الرئيسي</th>
                        <th class="py-3.5 px-3 sm:px-4">العلامات الحيوية</th>
                        <th class="py-3.5 px-3 sm:px-4">تاريخ الزيارة</th>
                        <th class="py-3.5 px-3 sm:px-4 text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($records as $rec)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-3 sm:px-4 font-bold text-hospital-700 whitespace-nowrap">
                            <span class="bg-teal-50 border border-teal-200 px-2.5 py-0.5 rounded font-mono">{{ $rec->record_number }}</span>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 whitespace-nowrap">
                            <a href="{{ route('patients.show', $rec->patient) }}" class="font-bold text-slate-800 hover:text-hospital-600 block">
                                {{ $rec->patient->full_name }}
                            </a>
                            <span class="text-[10px] text-slate-400">ملف: {{ $rec->patient->file_number }}</span>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 font-semibold text-slate-700 whitespace-nowrap">
                            {{ $rec->doctor->name }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-slate-600 whitespace-nowrap">
                            {{ $rec->clinic->name_ar ?? 'العيادة العامة' }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 max-w-xs font-medium text-slate-800 truncate" title="{{ $rec->diagnosis }}">
                            {{ $rec->diagnosis }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-[11px] text-slate-500 whitespace-nowrap">
                            @if($rec->blood_pressure || $rec->pulse_rate)
                                <span class="block">ضغط: {{ $rec->blood_pressure ?: '-' }}</span>
                                <span class="block">نبض: {{ $rec->pulse_rate ?: '-' }}</span>
                            @else
                                <span>-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-slate-600 font-mono whitespace-nowrap" dir="ltr">
                            {{ $rec->visit_date->format('Y-m-d') }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('medical-records.show', $rec) }}" title="عرض التقرير الطبي والوصفة" class="p-1.5 rounded-lg text-teal-600 hover:bg-teal-50 transition">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <a href="{{ route('medical-records.edit', $rec) }}" title="تعديل السجل" class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('medical-records.destroy', $rec) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذا السجل الطبي نهائياً؟');" class="inline">
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
                            <i class="fa-solid fa-file-medical text-3xl mb-2 text-slate-300 block"></i>
                            لا توجد سجلات طبية مسجلة
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $records->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
