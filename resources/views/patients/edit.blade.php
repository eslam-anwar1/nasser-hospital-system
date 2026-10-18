@extends('layouts.app')

@section('title', 'تعديل ملف المريض: ' . $patient->full_name)
@section('page_title', 'تعديل وتحديث بيانات المريض - مجمع ناصر الطبي')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">تعديل ملف المريض: {{ $patient->full_name }}</h3>
                    <p class="text-xs text-slate-500">رقم الملف: {{ $patient->file_number }}</p>
                </div>
            </div>
            <a href="{{ route('patients.show', $patient) }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                <i class="fa-solid fa-arrow-right"></i>
                <span>عرض الملف</span>
            </a>
        </div>

        <form action="{{ route('patients.update', $patient) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Basic Info -->
            <div>
                <h4 class="text-xs font-bold text-hospital-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-id-card ml-1"></i> البيانات الشخصية والتعريفية
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الملف الطبي <span class="text-rose-500">*</span></label>
                        <input type="text" name="file_number" value="{{ old('file_number', $patient->file_number) }}" required 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono font-bold text-hospital-800 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الاسم الرباعي للمريض <span class="text-rose-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name', $patient->full_name) }}" required 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهوية الوطنية / الإقامة <span class="text-rose-500">*</span></label>
                        <input type="text" name="national_id" value="{{ old('national_id', $patient->national_id) }}" required 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم هاتف المريض للتواصل <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" required dir="ltr"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500 text-right">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الجنس <span class="text-rose-500">*</span></label>
                        <select name="gender" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                            <option value="ذكر" {{ old('gender', $patient->gender) == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                            <option value="أنثى" {{ old('gender', $patient->gender) == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ الميلاد <span class="text-rose-500">*</span></label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth->format('Y-m-d')) }}" required 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">فصيلة الدم</label>
                        <select name="blood_group" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                            <option value="">غير محددة</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $patient->blood_group) == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">العنوان السكني</label>
                        <input type="text" name="address" value="{{ old('address', $patient->address) }}" 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                    </div>
                </div>
            </div>

            <!-- Section 2: Emergency Contact -->
            <div>
                <h4 class="text-xs font-bold text-hospital-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-phone-volume ml-1"></i> الاتصال في حالات الطوارئ
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">اسم شخص للطوارئ / صلة القرابة</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">هاتف الطوارئ</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" dir="ltr"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500 text-right">
                    </div>
                </div>
            </div>

            <!-- Section 3: Health History -->
            <div>
                <h4 class="text-xs font-bold text-hospital-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-notes-medical ml-1"></i> التاريخ المرضي والتحذيرات الطبية
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">حساسية الأدوية أو الأغذية (Allergies)</label>
                        <textarea name="known_allergies" rows="3" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">{{ old('known_allergies', $patient->known_allergies) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الأمراض المزمنة (Chronic Diseases)</label>
                        <textarea name="chronic_diseases" rows="3" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">{{ old('chronic_diseases', $patient->chronic_diseases) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('patients.show', $patient) }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>تحديث وحفظ التعديلات</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
