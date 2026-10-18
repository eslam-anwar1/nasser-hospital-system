@extends('layouts.app')

@section('title', 'فتح ملف طبي جديد')
@section('page_title', 'تسجيل مريض وفتح ملف طبي - مجمع ناصر الطبي')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-hospital-100 text-hospital-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">بيانات الملف الطبي للمريض</h3>
                    <p class="text-xs text-slate-500">يرجى تسجيل كافة البيانات الشخصية والطبية بدقة وفق بطاقة الهوية الرسمية</p>
                </div>
            </div>
            <a href="{{ route('patients.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                <i class="fa-solid fa-arrow-right"></i>
                <span>العودة للسجلات</span>
            </a>
        </div>

        <form action="{{ route('patients.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Section 1: Basic Info -->
            <div>
                <h4 class="text-xs font-bold text-hospital-700 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">
                    <i class="fa-solid fa-id-card ml-1"></i> البيانات الشخصية والتعريفية
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الملف الطبي <span class="text-rose-500">*</span></label>
                        <input type="text" name="file_number" value="{{ old('file_number', $suggestedFileNumber) }}" required 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono font-bold text-hospital-800 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الاسم الرباعي للمريض <span class="text-rose-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" required 
                               placeholder="الاسم الأول - اسم الأب - اسم الجد - العائلة"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهوية الوطنية / الإقامة <span class="text-rose-500">*</span></label>
                        <input type="text" name="national_id" value="{{ old('national_id') }}" required 
                               placeholder="مثال: 401234567"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم هاتف المريض للتواصل <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" required dir="ltr"
                               placeholder="0599xxxxxx"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500 text-right">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الجنس <span class="text-rose-500">*</span></label>
                        <select name="gender" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                            <option value="">اختر الجنس</option>
                            <option value="ذكر" {{ old('gender') == 'ذكر' ? 'selected' : '' }}>ذكر</option>
                            <option value="أنثى" {{ old('gender') == 'أنثى' ? 'selected' : '' }}>أنثى</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">تاريخ الميلاد <span class="text-rose-500">*</span></label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" required 
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">فصيلة الدم</label>
                        <select name="blood_group" class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                            <option value="">غير محددة</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group') == $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">العنوان السكني</label>
                        <input type="text" name="address" value="{{ old('address') }}" 
                               placeholder="المدينة - الحي - الشارع"
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
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}" 
                               placeholder="مثال: أحمد (شقيق المريض)"
                               class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">هاتف الطوارئ</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}" dir="ltr"
                               placeholder="0599xxxxxx"
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
                        <textarea name="known_allergies" rows="3" 
                                  placeholder="مثال: حساسية بنسلين، حساسية من أدوية السلفا..." 
                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">{{ old('known_allergies') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">الأمراض المزمنة (Chronic Diseases)</label>
                        <textarea name="chronic_diseases" rows="3" 
                                  placeholder="مثال: سكري، ضغط، ربو، أمراض كلى..." 
                                  class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">{{ old('chronic_diseases') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('patients.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-hospital-600 hover:bg-hospital-700 text-white text-xs font-bold shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-save"></i>
                    <span>حفظ وفتح الملف الطبي</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
