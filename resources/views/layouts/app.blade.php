<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'الرئيسية') - مجمع ناصر الطبي | Nasser Hospital System</title>

    <!-- Google Fonts: Cairo & Tajawal -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Tailwind CSS CDN for instant robust styling & custom hospital palette -->
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
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                            950: '#042f2e',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between">

    <!-- Mobile Navigation Overlay -->
    <div id="mobile-sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 z-40 hidden md:hidden transition-opacity duration-300"></div>

    <div class="min-h-screen flex flex-col md:flex-row relative">
        <!-- Sidebar Navigation -->
        <aside id="sidebar" class="fixed inset-y-0 right-0 z-50 w-72 md:w-64 bg-slate-900 text-white flex flex-col flex-shrink-0 shadow-2xl md:shadow-xl transform translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out md:static md:z-30">
            <!-- Brand / Hospital Logo -->
            <div class="h-20 bg-hospital-900 flex items-center justify-between px-5 md:px-6 border-b border-hospital-800">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 md:w-11 md:h-11 rounded-xl bg-white/10 flex items-center justify-center text-teal-300 border border-white/20 shadow-inner">
                        <i class="fa-solid fa-hospital text-xl md:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-sm md:text-base font-bold leading-tight tracking-wide text-white">مجمع ناصر الطبي</h1>
                        <span class="text-[11px] md:text-xs text-teal-300/80 font-medium">نظام الإدارة الاستشفائية</span>
                    </div>
                </div>
                <!-- Close Button (Mobile Only) -->
                <button type="button" id="close-sidebar-btn" class="md:hidden text-teal-200 hover:text-white p-2 rounded-lg hover:bg-hospital-800">
                    <i class="fa-solid fa-xmark text-xl"></i>
                </button>
            </div>

            <!-- Current User Badge -->
            <div class="p-3.5 mx-3 my-3 rounded-xl bg-slate-800/80 border border-slate-700/60 flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-hospital-600/30 text-hospital-400 border border-hospital-500/40 flex items-center justify-center font-bold text-sm shrink-0">
                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden min-w-0">
                    <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-block text-[10px] px-2 py-0.5 mt-0.5 rounded-full bg-hospital-500/20 text-hospital-300 border border-hospital-500/30">
                        {{ auth()->user()->role_arabic }}
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('dashboard') ? 'bg-hospital-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-base shrink-0"></i>
                    <span>لوحة التحكم الطبية</span>
                </a>

                <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    السجلات والملفات
                </div>

                <a href="{{ route('patients.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('patients.*') ? 'bg-hospital-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-injured w-5 text-center text-base shrink-0"></i>
                    <span>سجلات المرضى</span>
                </a>

                <a href="{{ route('appointments.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('appointments.*') ? 'bg-hospital-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-calendar-check w-5 text-center text-base shrink-0"></i>
                    <span>جدول المواعيد والكشوفات</span>
                </a>

                <a href="{{ route('medical-records.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('medical-records.*') ? 'bg-hospital-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-medical w-5 text-center text-base shrink-0"></i>
                    <span>السجلات والاستشارات الطبية</span>
                </a>

                <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    إدارة المستشفى
                </div>

                <a href="{{ route('clinics.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('clinics.*') ? 'bg-hospital-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clinic-medical w-5 text-center text-base shrink-0"></i>
                    <span>العيادات والأقسام الطبية</span>
                </a>

                @if(auth()->user()->isAdmin())
                <a href="{{ route('users.index') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm font-semibold transition-colors {{ request()->routeIs('users.*') ? 'bg-hospital-600 text-white shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-user-doctor w-5 text-center text-base shrink-0"></i>
                    <span>الكادر الطبي والموظفين</span>
                </a>
                @endif
            </nav>

            <!-- Logout Section -->
            <div class="p-3 border-t border-slate-800">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-bold text-rose-300 hover:bg-rose-500/10 hover:text-rose-200 border border-rose-500/20 transition-all">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>تسجيل الخروج الآمن</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-slate-50 min-h-screen">
            <!-- Header Top Bar -->
            <header class="min-h-16 md:h-20 bg-white border-b border-slate-200/80 px-4 sm:px-6 md:px-8 py-3 md:py-0 flex flex-wrap items-center justify-between gap-3 shadow-sm z-20">
                <div class="flex items-center gap-3">
                    <!-- Hamburger Menu Button (Mobile) -->
                    <button type="button" id="mobile-menu-btn" class="md:hidden inline-flex items-center justify-center p-2 rounded-xl text-slate-600 hover:text-hospital-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-hospital-500">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h2 class="text-base sm:text-lg md:text-xl font-bold text-slate-800 leading-tight">@yield('page_title', 'لوحة المعلومات الطبية')</h2>
                        <span class="inline-block md:hidden text-[10px] px-2 py-0.5 rounded bg-teal-50 text-teal-700 font-semibold border border-teal-200 mt-0.5">
                            مجمع ناصر الطبي
                        </span>
                    </div>
                    <span class="hidden md:inline-block text-xs px-2.5 py-1 rounded bg-teal-50 text-teal-700 font-semibold border border-teal-200">
                        مستشفى حكومي تخصصي
                    </span>
                </div>

                <div class="flex items-center gap-2 sm:gap-4 mr-auto md:mr-0">
                    <div class="text-left hidden lg:block">
                        <div class="text-xs text-slate-500 font-medium">التاريخ والوقت التشغيلي</div>
                        <div class="text-sm font-bold text-slate-700" id="live-clock">
                            {{ now()->translatedFormat('l، d F Y') }}
                        </div>
                    </div>

                    <a href="{{ route('appointments.create') }}" class="inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 rounded-lg bg-hospital-600 text-white text-xs sm:text-sm font-bold shadow-md hover:bg-hospital-700 transition">
                        <i class="fa-solid fa-plus text-xs"></i>
                        <span>حجز موعد طبي</span>
                    </a>
                </div>
            </header>

            <!-- Alerts / Flash Messages -->
            <div class="px-4 sm:px-6 md:px-8 pt-4">
                @if(session('success'))
                <div class="p-3 sm:p-4 mb-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-2.5 sm:gap-3">
                        <i class="fa-solid fa-circle-check text-emerald-600 text-base sm:text-lg shrink-0"></i>
                        <span class="text-xs sm:text-sm font-bold">{{ session('success') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 p-1">
                        <i class="fa-solid fa-times"></i>
                    </button>
                </div>
                @endif

                @if(session('error') || $errors->any())
                <div class="p-3 sm:p-4 mb-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 shadow-sm">
                    <div class="flex items-center gap-2.5 sm:gap-3 mb-1">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600 text-base sm:text-lg shrink-0"></i>
                        <span class="text-xs sm:text-sm font-bold">يرجى مراجعة الملاحظات التالية:</span>
                    </div>
                    @if(session('error'))
                        <div class="text-xs font-semibold mr-6 sm:mr-7 text-rose-700">{{ session('error') }}</div>
                    @endif
                    @if($errors->any())
                    <ul class="list-disc list-inside mr-6 sm:mr-7 text-xs font-medium text-rose-700 space-y-1">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endif
            </div>

            <!-- Dynamic Content -->
            <main class="flex-1 px-4 sm:px-6 md:px-8 py-4 overflow-y-auto">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 px-4 sm:px-6 md:px-8 py-4 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
                <div>
                    جميع الحقوق محفوظة &copy; {{ date('Y') }} - <span class="font-bold text-slate-700">مجمع ناصر الطبي</span> (وزارة الصحة)
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 text-emerald-600 font-semibold text-[11px] sm:text-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        النظام الطبي في وضع الاستعداد والتشغيل
                    </span>
                </div>
            </footer>
        </div>
    </div>

    <!-- Responsive Drawer JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const closeSidebarBtn = document.getElementById('close-sidebar-btn');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');

            function openSidebar() {
                sidebar.classList.remove('translate-x-full');
                backdrop.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('translate-x-full');
                backdrop.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', openSidebar);
            }
            if (closeSidebarBtn) {
                closeSidebarBtn.addEventListener('click', closeSidebar);
            }
            if (backdrop) {
                backdrop.addEventListener('click', closeSidebar);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
