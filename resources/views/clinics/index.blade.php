@extends('layouts.app')

@section('title', 'العيادات والأقسام الطبية')
@section('page_title', 'أقسام وعيادات مجمع ناصر الطبي')

@section('content')
<div class="space-y-6">

    <!-- Top Stats and Add Clinic Button -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
            <h3 class="text-sm font-bold text-slate-800">الأقسام والعيادات التخصصية العاملة بمجمع ناصر</h3>
            <p class="text-xs text-slate-500">إدارة الأقسام الاستشفائية والعيادات الخارجية وتحديد الرسوم والمواقع</p>
        </div>

        <button onclick="document.getElementById('addClinicModal').classList.remove('hidden')" class="px-5 py-2.5 bg-hospital-600 text-white rounded-xl text-xs font-bold shadow-md hover:bg-hospital-700 transition flex items-center gap-2">
            <i class="fa-solid fa-plus"></i>
            <span>إضافة عيادة / قسم طبي</span>
        </button>
    </div>

    <!-- Clinics Grid Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @foreach($clinics as $clinic)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="px-2.5 py-0.5 rounded-lg text-xs font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $clinic->code }}
                    </span>
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $clinic->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                        {{ $clinic->is_active ? 'نشط ويعمل' : 'مغلق للصيانة' }}
                    </span>
                </div>

                <h4 class="text-base font-bold text-slate-800 mb-1">{{ $clinic->name_ar }}</h4>
                @if($clinic->name_en)
                    <p class="text-xs text-slate-400 font-medium mb-3">{{ $clinic->name_en }}</p>
                @endif

                <p class="text-xs text-slate-600 leading-relaxed mb-4 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    {{ $clinic->description ?: 'لا يوجد وصف تفصيلي للقسم.' }}
                </p>

                <div class="space-y-1.5 text-xs text-slate-600 border-t border-slate-100 pt-3">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400"><i class="fa-solid fa-location-dot ml-1 text-slate-400"></i> موقع القسم:</span>
                        <span class="font-semibold text-slate-700">{{ $clinic->location ?? 'المبنى الرئيسي' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400"><i class="fa-solid fa-money-bill-wave ml-1 text-slate-400"></i> رسوم الكشف:</span>
                        <span class="font-bold text-hospital-800">{{ number_format($clinic->consultation_fee, 2) }} ₪</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-400"><i class="fa-solid fa-calendar-check ml-1 text-slate-400"></i> إجمالي الحجوزات:</span>
                        <span class="font-bold text-slate-700">{{ $clinic->appointments_count }} موعد</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 pt-3 border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('appointments.create', ['clinic_id' => $clinic->id]) }}" class="text-xs font-bold text-hospital-600 hover:text-hospital-800 flex items-center gap-1">
                    <i class="fa-solid fa-calendar-plus"></i>
                    <span>حجز موعد بالقسم</span>
                </a>

                <form action="{{ route('clinics.destroy', $clinic) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذه العيادة؟');" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-rose-500 hover:text-rose-700 text-xs p-1">
                        <i class="fa-solid fa-trash-can"></i>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

</div>

<!-- Modal: Add New Clinic -->
<div id="addClinicModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-white/20">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
            <h3 class="text-base font-bold text-slate-800">إضافة قسم / عيادة طبية جديدة</h3>
            <button onclick="document.getElementById('addClinicModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <form action="{{ route('clinics.store') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">رمز القسم (الكود) <span class="text-rose-500">*</span></label>
                    <input type="text" name="code" required placeholder="CLN-NEW" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">رسوم الكشف (₪) <span class="text-rose-500">*</span></label>
                    <input type="number" step="0.01" name="consultation_fee" value="50.00" required class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">اسم القسم / العيادة بالعربية <span class="text-rose-500">*</span></label>
                <input type="text" name="name_ar" required placeholder="مثال: قسم العيون وجراحة الليزر" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">الاسم بالإنجليزية</label>
                <input type="text" name="name_en" placeholder="e.g. Ophthalmology & Eye Clinic" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">الموقع داخل المجمع</label>
                <input type="text" name="location" placeholder="مثال: المبنى الجراحي - الطابق الثاني" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">نبذة عن القسم والخدمات</label>
                <textarea name="description" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-300 text-xs"></textarea>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" checked value="1" class="rounded border-slate-300 text-hospital-600 focus:ring-hospital-500">
                <label for="is_active" class="text-xs font-bold text-slate-700 cursor-pointer">القسم نشط ومتاح لحجز المواعيد</label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                <button type="button" onclick="document.getElementById('addClinicModal').classList.add('hidden')" class="px-4 py-2 rounded-xl border text-xs font-bold text-slate-600">إلغاء</button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-hospital-600 hover:bg-hospital-700 text-white text-xs font-bold shadow-md">إضافة العيادة</button>
            </div>
        </form>
    </div>
</div>
@endsection
