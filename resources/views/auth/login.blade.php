<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }} - 7expres</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        [dir="rtl"] { font-family: 'Cairo', sans-serif; }
        [dir="ltr"] { font-family: 'Inter', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 60%, #0ea5e9 100%); }
    </style>
</head>
<body class="min-h-screen gradient-bg flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-2xl mb-4">
                <span class="text-blue-900 font-black text-3xl">7X</span>
            </div>
            <h1 class="text-white font-black text-3xl">7expres</h1>
            <p class="text-blue-200 text-sm mt-1">
                {{ app()->getLocale() === 'ar' ? 'منصة إدارة الشحنات اللوجستية' : 'Logistics Shipment Platform' }}
            </p>
            <p class="text-blue-300 text-xs mt-0.5">Malaysia ⇌ Yemen</p>
        </div>

        {{-- Form card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
            </h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="email" name="email" value="{{ old('email') }}"
                               class="w-full rounded-xl border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }}
                                      {{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }} py-3 text-sm outline-none
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition"
                               placeholder="{{ app()->getLocale() === 'ar' ? 'admin@7expres.com' : 'admin@7expres.com' }}"
                               autofocus required>
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}
                    </label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute {{ app()->getLocale() === 'ar' ? 'right-3' : 'left-3' }} top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="password" name="password"
                               class="w-full rounded-xl border border-gray-300
                                      {{ app()->getLocale() === 'ar' ? 'pr-10' : 'pl-10' }} py-3 text-sm outline-none
                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-100 transition"
                               required>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600">
                        {{ app()->getLocale() === 'ar' ? 'تذكرني' : 'Remember me' }}
                    </label>
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all duration-150 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
                </button>
            </form>

            {{-- Demo credentials --}}
            <div class="mt-6 p-4 bg-blue-50 rounded-xl text-center">
                <p class="text-xs text-blue-700 font-semibold mb-1">
                    {{ app()->getLocale() === 'ar' ? 'بيانات الدخول التجريبية' : 'Demo Credentials' }}
                </p>
                <p class="text-xs text-blue-600 font-mono">admin@7expres.com / password</p>
            </div>
        </div>

        {{-- Language toggle --}}
        <div class="text-center mt-4">
            <a href="{{ route('locale.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
               class="text-blue-200 hover:text-white text-sm transition">
                <i class="fa-solid fa-globe me-1"></i>
                {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
            </a>
        </div>

        {{-- Public tracking link --}}
        <div class="text-center mt-2">
            <a href="{{ route('tracking') }}" class="text-blue-200 hover:text-white text-sm transition">
                <i class="fa-solid fa-search me-1"></i>
                {{ app()->getLocale() === 'ar' ? 'تتبع شحنة' : 'Track a Shipment' }}
            </a>
        </div>
    </div>

</body>
</html>
