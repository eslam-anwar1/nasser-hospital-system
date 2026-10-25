@extends('layouts.app')

@section('title', 'الكادر الطبي والموظفين')
@section('page_title', 'إدارة الكادر الطبي والوظيفي - مجمع ناصر الطبي')

@section('content')
<div class="space-y-6">

    <!-- Actions & Filter Bar -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('users.index') }}" class="w-full md:flex-1 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 sm:gap-3">
            <div class="relative w-full sm:w-72 md:w-80">
                <i class="fa-solid fa-magnifying-glass absolute right-3.5 top-3 text-slate-400 text-xs"></i>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="ابحث بالاسم، الرقم الوظيفي..." 
                       class="w-full pl-4 pr-9 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-hospital-500">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <select name="role" class="w-full sm:w-auto py-2 px-3 rounded-xl border border-slate-200 text-xs text-slate-700">
                    <option value="">جميع الأدوار</option>
                    <option value="Admin" {{ request('role') == 'Admin' ? 'selected' : '' }}>مدير النظام</option>
                    <option value="Doctor" {{ request('role') == 'Doctor' ? 'selected' : '' }}>طبيب استشاري / أخصائي</option>
                    <option value="Receptionist" {{ request('role') == 'Receptionist' ? 'selected' : '' }}>موظف استقبال</option>
                    <option value="Accountant" {{ request('role') == 'Accountant' ? 'selected' : '' }}>محاسب</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition shrink-0">
                    تصفية
                </button>
            </div>
        </form>

        <a href="{{ route('users.create') }}" class="w-full md:w-auto px-5 py-2.5 bg-hospital-600 text-white rounded-xl text-xs font-bold shadow-md hover:bg-hospital-700 transition flex items-center justify-center gap-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>إضافة كادر طبي / إداري</span>
        </a>
    </div>

    <!-- Staff Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-800">قائمة الكادر الاستشفائي المسجل ({{ $users->total() }})</h3>
        </div>

        <div class="overflow-x-auto w-full">
            <table class="w-full text-right text-xs min-w-[750px]">
                <thead class="bg-slate-50/80 text-slate-500 font-bold border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-3 sm:px-4">الرقم الوظيفي</th>
                        <th class="py-3.5 px-3 sm:px-4">الاسم الكامل</th>
                        <th class="py-3.5 px-3 sm:px-4">اسم المستخدم</th>
                        <th class="py-3.5 px-3 sm:px-4">الدور الوظيفي</th>
                        <th class="py-3.5 px-3 sm:px-4">التخصص الطبي</th>
                        <th class="py-3.5 px-3 sm:px-4">البريد الإلكتروني</th>
                        <th class="py-3.5 px-3 sm:px-4">حالة الحساب</th>
                        <th class="py-3.5 px-3 sm:px-4 text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/60 transition">
                        <td class="py-3.5 px-3 sm:px-4 font-bold text-hospital-700 whitespace-nowrap">
                            <span class="bg-teal-50 border border-teal-200 px-2.5 py-0.5 rounded font-mono">{{ $user->employee_id }}</span>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 font-bold text-slate-800 whitespace-nowrap">
                            {{ $user->name }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 font-mono text-slate-600 whitespace-nowrap">{{ $user->username }}</td>
                        <td class="py-3.5 px-3 sm:px-4 whitespace-nowrap">
                            @if($user->isAdmin())
                                <span class="px-2 py-0.5 rounded bg-purple-50 text-purple-700 font-bold text-[10px] border border-purple-200">مدير عام</span>
                            @elseif($user->isDoctor())
                                <span class="px-2 py-0.5 rounded bg-teal-50 text-teal-700 font-bold text-[10px] border border-teal-200">طبيب استشاري</span>
                            @else
                                <span class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 font-bold text-[10px] border border-blue-200">{{ $user->role_arabic }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-slate-600 whitespace-nowrap">
                            {{ $user->specialization ?: '-' }}
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-slate-600 font-mono whitespace-nowrap" dir="ltr">{{ $user->email }}</td>
                        <td class="py-3.5 px-3 sm:px-4 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                {{ $user->is_active ? 'نشط' : 'معطل' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-3 sm:px-4 text-center whitespace-nowrap">
                            <div class="inline-flex items-center gap-1.5">
                                <a href="{{ route('users.edit', $user) }}" title="تعديل الحساب" class="p-1.5 rounded-lg text-amber-600 hover:bg-amber-50 transition">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذا الموظف من سجلات المستشفى؟');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="حذف" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-400">
                            لا يوجد موظفون مطابقون لخيارات البحث
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
