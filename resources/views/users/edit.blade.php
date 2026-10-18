@extends('layouts.app')

@section('title', 'تعديل بيانات الموظف: ' . $user->name)
@section('page_title', 'تعديل بيانات الكادر - مجمع ناصر الطبي')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-user-pen"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">تعديل بيانات: {{ $user->name }}</h3>
                    <p class="text-xs text-slate-500">الرقم الوظيفي: {{ $user->employee_id }}</p>
                </div>
            </div>
            <a href="{{ route('users.index') }}" class="text-xs font-bold text-slate-500 hover:text-slate-800 flex items-center gap-1">
                <i class="fa-solid fa-arrow-right"></i>
                <span>قائمة الموظفين</span>
            </a>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">الرقم الوظيفي <span class="text-rose-500">*</span></label>
                    <input type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}" required 
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono font-bold text-hospital-800 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-hospital-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">الاسم الكامل واللقب الطبي <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">اسم المستخدم لتسجيل الدخول <span class="text-rose-500">*</span></label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required dir="ltr"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500 text-right">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">البريد الإلكتروني الرسمي <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required dir="ltr"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500 text-right">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">كلمة المرور الجديدة (اتركها فارغة للإبقاء على الحالية)</label>
                    <input type="password" name="password" placeholder="••••••••" 
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">المسمى والدور الوظيفي <span class="text-rose-500">*</span></label>
                    <select name="role" required class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                        <option value="Doctor" {{ old('role', $user->role) == 'Doctor' ? 'selected' : '' }}>طبيب استشاري / أخصائي</option>
                        <option value="Receptionist" {{ old('role', $user->role) == 'Receptionist' ? 'selected' : '' }}>موظف استقبال وتنسيق</option>
                        <option value="Accountant" {{ old('role', $user->role) == 'Accountant' ? 'selected' : '' }}>محاسب مالي</option>
                        <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>مدير النظام العام</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">التخصص الطبي الدقيق</label>
                    <input type="text" name="specialization" value="{{ old('specialization', $user->specialization) }}" 
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-hospital-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">رقم الهاتف للتواصل</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" dir="ltr"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs font-mono focus:ring-2 focus:ring-hospital-500 text-right">
                </div>
            </div>

            <div class="pt-2">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="rounded border-slate-300 text-hospital-600 focus:ring-hospital-500">
                    <span class="text-xs font-bold text-slate-700">الحساب نشط ومصرح له بالدخول</span>
                </label>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 text-xs font-bold hover:bg-slate-50 transition">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i>
                    <span>تحديث البيانات</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
