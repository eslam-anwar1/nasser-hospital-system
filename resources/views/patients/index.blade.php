@extends('layouts.app')

@section('title', 'سجلات المرضى')
@section('page_title', 'إدارة سجلات وملفات المرضى - مجمع ناصر الطبي')

@section('content')
<div class="space-y-6">

    <!-- Actions and Search Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <!-- Search form -->
        <form method="GET" action="{{ route('patients.index') }}" class="w-full md:flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3">
            <div class="relative w-full sm:w-72 md:w-80">
                <i class="fa-solid fa-magnifying-glass absolute right-3.5 top-3 text-slate-400 text-xs"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="ابحث بالاسم، رقم الملف، الهوية..." 
                       class="w-full pl-4 pr-9 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-hospital-500 focus:border-hospital-500">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="blood_group" class="w-full sm:w-36 py-2 px-3 rounded-xl border border-slate-200 text-xs text-slate-700 focus:ring-2 focus:ring-hospital-500">
                    <option value="">جميع فصائل الدم</option>
                    @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                        <option value="{{ $bg }}" {{ request('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition flex items-center justify-center gap-1.5 shrink-0">
                    <i class="fa-solid fa-filter"></i>
                    <span>تصفية</span>
                </button>

                @if(request()->hasAny(['search', 'blood_group']))
                    <a href="{{ route('patients.index') }}" class="text-xs text-slate-500 hover:text-rose-600 font-bold shrink-0">إلغاء</a>
                @endif
            </div>
        </form>

        <a href="{{ route('patients.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-hospital-600 text-white rounded-xl text-xs font-bold shadow-md hover:bg-hospital-700 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>فتح ملف طبي جديد</span>
        </a>
    </div>

    <!-- Patients Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">قائمة المرضى المسجلين بالمستشفى ({{ $patients->total() }})</h3>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-right text-xs min-w-[750px]">
                <thead class="bg-slate-50/80 text-slate-500 font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-3 sm:px-4">رقم الملف</th>
                        <th class="py-3.5 px-3 sm:px-4">اسم المريض الكامل</th>
                        <th class="py-3.5 px-3 sm:px-4">رقم الهوية الوطنية</th>
                        <th class="py-3.5 px-3 sm:px-4">رقم الهاتف</th>
                        <th class="py-3.5 px-3 sm:px-4">الجنس / العمر</th>
                        <th class="py-3.5 px-3 sm:px-4">فصيلة الدم</th>
                        <th class="py-3.5 px-3 sm:px-4">تاريخ فتح الملف</th>
                        <th class="py-3.5 px-3 sm:px-4 text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($patients as $patient)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-3 sm:px-4 font-bold text-hospital-700 whitespace-nowrap">
                            <span class="bg-teal-50 border border-teal-200 px-2.5 py-0.5 rounded-lg text-xs">{{ $patient->file_number }}</span>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-800 whitespace-nowrap">
                            <a href="{{ route('patients.show', $patient) }}" class="hover:text-hospital-600 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 text-xs shrink-0">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <span>{{ $patient->full_name }}</span>
                            </a>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-slate-600 font-mono text-[11px] whitespace-nowrap">{{ $patient->national_id }}</td>
                        <td class="py-3.5 px-3 sm:px-4 text-slate-600 font-mono text-[11px] whitespace-nowrap" dir="ltr">{{ $patient->phone }}</td>
                        <td class="py-3.5 px-3 sm:px-4 text-slate-600 whitespace-nowrap">
                            <span class="font-semibold">{{ $patient->gender_arabic }}</span> ({{ $patient->age }} سنة)
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 whitespace-nowrap">
                            @if($patient->blood_group)
                                <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 font-bold text-[11px] border border-rose-200">
                                    {{ $patient->blood_group }}
                                </span>
                            @else
                                <span class="text-slate-400">-</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-slate-500 whitespace-nowrap" dir="ltr">
                            {{ $patient->created_at->format('Y-m-d') }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('patients.show', $patient) }}" title="عرض الملف الطبي الكامل" class="p-1.5 rounded-lg text-teal-600 hover:bg-teal-50 transition">
                                    <i class="fa-solid fa-folder-open"></i>
                                </a>
                                <a href="{{ route('appointments.create', ['patient_id' => $patient->id]) }}" title="حجز كشف طبي" class="p-1.5 rounded-lg text-blue-600 hover:bg-blue-50 transition">
                                    <i class="fa-solid fa-calendar-plus"></i>
                                </a>
                                <a href="{{ route('patients.edit', $patient) }}" title="تعديل البيانات" class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('patients.destroy', $patient) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف ملف هذا المريض نهائياً من مجمع ناصر الطبي؟');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="حذف الملف" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 block text-slate-300"></i>
                            لا توجد ملفات مرضى مطابقة لخيارات البحث
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($patients->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $patients->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
