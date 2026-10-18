<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('specialization', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $lastUser = User::orderBy('id', 'desc')->first();
        $suggestedEmpId = 'EMP-' . ($lastUser ? ($lastUser->id + 101) : 101);

        return view('users.create', compact('suggestedEmpId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', 'unique:users,employee_id'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:Admin,Doctor,Receptionist,Accountant'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['boolean'],
        ], [
            'employee_id.required' => 'الرقم الوظيفي مطلوب.',
            'employee_id.unique' => 'الرقم الوظيفي مستخدم مسبقاً.',
            'name.required' => 'اسم الموظف أو الطبيب مطلوب.',
            'username.required' => 'اسم المستخدم مطلوب.',
            'username.unique' => 'اسم المستخدم مأخوذ بالفعل.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.unique' => 'البريد الإلكتروني مسجل مسبقاً.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'يجب ألا تقل كلمة المرور عن 6 أحرف.',
            'role.required' => 'يرجى اختيار المسمى والدور الوظيفي.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'تم إضافة الكادر الطبي / الإداري بنجاح.');
    }

    public function show(User $user)
    {
        $user->load(['appointments.patient', 'medicalRecords.patient']);

        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'string', Rule::unique('users')->ignore($user->id)],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'in:Admin,Doctor,Receptionist,Accountant'],
            'specialization' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['boolean'],
        ], [
            'employee_id.required' => 'الرقم الوظيفي مطلوب.',
            'name.required' => 'اسم الموظف أو الطبيب مطلوب.',
            'username.required' => 'اسم المستخدم مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'تم تحديث بيانات الموظف بنجاح.');
    }

    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['error' => 'لا يمكنك حذف حسابك الشخصي الحالي أثناء تسجيل الدخول.']);
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', "تم حذف حساب ({$name}) من الكادر الطبي.");
    }
}
