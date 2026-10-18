<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تسجيل الدخول - مجمع ناصر الطبي | Nasser Hospital System</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Cairo', 'sans-serif'],
                    },
                    colors: {
                        hospital: {
                            50: '#f0fdfa',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-teal-950 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full">
        <!-- Logo and Hospital Identity -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-hospital-600/20 text-teal-400 border border-teal-500/30 shadow-2xl backdrop-blur-sm mb-4">
                <i class="fa-solid fa-hospital text-4xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-wide">مجمع ناصر الطبي</h1>
            <p class="text-sm text-teal-200/80 font-medium mt-1">البوابة المركزية لإدارة الخدمات الاستشفائية والعيادات</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
            <div class="border-b border-slate-100 pb-4 mb-6">
                <h2 class="text-lg font-bold text-slate-800">تسجيل دخول الكادر الطبي والإداري</h2>
                <p class="text-xs text-slate-500 mt-1">يرجى إدخال اسم المستخدم وكلمة المرور للدخول إلى سجلات المستشفى</p>
            </div>

            @if(session('success'))
                <div class="p-3 mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-3 mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                            <li><i class="fa-solid fa-circle-exclamation ml-1"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="login" class="block text-xs font-bold text-slate-700 mb-1.5">
                        <i class="fa-solid fa-id-badge ml-1 text-hospital-600"></i> اسم المستخدم أو البريد الإلكتروني
                    </label>
                    <input type="text" 
                           id="login" 
                           name="login" 
                           value="{{ old('login', 'admin') }}" 
                           required 
                           autocomplete="username"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-hospital-500 focus:border-hospital-500 text-sm font-medium text-slate-800 transition" 
                           placeholder="مثال: admin أو admin@nasser.hospital">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5">
                        <i class="fa-solid fa-lock ml-1 text-hospital-600"></i> كلمة المرور
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           value="12345678"
                           required 
                           autocomplete="current-password"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-hospital-500 focus:border-hospital-500 text-sm font-medium text-slate-800 transition" 
                           placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-slate-600 font-medium">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-hospital-600 focus:ring-hospital-500">
                        <span>تذكر جلستي على هذا الجهاز</span>
                    </label>
                    <span class="text-teal-700 font-semibold cursor-help" title="تواصل مع قسم تكنولوجيا المعلومات بالمستشفى لإعادة ضبط كلمة المرور">الدعم الفني الطبي</span>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-hospital-600 hover:bg-hospital-700 text-white font-bold text-sm shadow-lg shadow-hospital-600/30 transition duration-150 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>دخول النظام الطبي</span>
                </button>
            </form>

            <!-- Quick Demo Credentials Card -->
            <div class="mt-6 pt-5 border-t border-slate-100 bg-slate-50/70 -mx-8 -mb-8 p-6 rounded-b-2xl">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-700 mb-2">
                    <i class="fa-solid fa-key text-hospital-600"></i>
                    <span>بيانات اعتماد مدير النظام الافتراضية:</span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs text-slate-600">
                    <div class="bg-white p-2 rounded-lg border border-slate-200">
                        <span class="text-[10px] text-slate-400 block">المستخدم:</span>
                        <code class="font-bold text-slate-800">admin</code>
                    </div>
                    <div class="bg-white p-2 rounded-lg border border-slate-200">
                        <span class="text-[10px] text-slate-400 block">كلمة المرور:</span>
                        <code class="font-bold text-slate-800">12345678</code>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-6 text-xs text-teal-200/60 font-medium">
            مجمع ناصر الطبي &bull; وزارة الصحة &bull; فلسطين
        </div>
    </div>

</body>
</html>
