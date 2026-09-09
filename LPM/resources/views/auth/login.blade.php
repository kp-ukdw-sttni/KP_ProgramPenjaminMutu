<x-guest-layout>
    <div class="grid lg:grid-cols-2 min-h-screen">
        <!-- Left Side -->
        <div class="hidden lg:flex flex-col justify-center items-center bg-brand-900 text-white p-12 relative overflow-hidden">
            <div class="absolute inset-0 bg-brand-950 opacity-50 pointer-events-none"></div>
            <div class="relative z-10 text-center flex flex-col items-center">
                <div class="w-24 h-24 bg-white rounded-2xl p-2 mb-8 shadow-2xl flex items-center justify-center">
                    <img src="{{ asset('logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <h1 class="text-4xl font-bold mb-4 tracking-tight">Sistem Evaluasi<br><span class="text-brand-300">Standar Mutu</span></h1>
                <p class="text-brand-100 text-lg max-w-md">Platform digital terintegrasi untuk monitoring dan evaluasi penjaminan mutu internal.</p>
            </div>
            
            <!-- Decorative Elements -->
            <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-brand-500/20 blur-3xl"></div>
            <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-brand-400/20 blur-3xl"></div>
        </div>

        <!-- Right Side -->
        <div class="flex flex-col justify-center items-center bg-white p-8 sm:p-12 lg:p-24 relative">
            <div class="w-full max-w-md">
                <div class="lg:hidden flex flex-col items-center mb-8">
                    <div class="w-16 h-16 bg-white rounded-xl shadow p-1 mb-4 flex items-center justify-center">
                        <img src="{{ asset('logo.png') }}" alt="Logo" class="w-full h-full object-contain">
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 text-center">Sistem Evaluasi Standar Mutu</h2>
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                    <p class="text-gray-500">Silakan masuk ke akun Anda untuk melanjutkan.</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" required autofocus autocomplete="username" value="{{ old('email') }}" 
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all duration-200 ease-in-out hover:shadow-md">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required autocomplete="current-password"
                                class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-brand-500 focus:border-brand-500 sm:text-sm transition-all duration-200 ease-in-out hover:shadow-md">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-brand-600 focus:ring-brand-500 border-gray-300 rounded cursor-pointer">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700 cursor-pointer">
                                {{ __('Ingat Saya') }}
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-brand-600 hover:text-brand-500 transition-colors">
                                    {{ __('Lupa password?') }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 transition-all duration-200 ease-in-out hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition-all duration-200 ease-in-out hover:shadow-md">
                            {{ __('Masuk') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>


