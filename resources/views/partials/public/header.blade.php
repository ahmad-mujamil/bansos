@php
    $extendedNav = $extendedNav ?? false;
@endphp
<nav class="fixed top-0 w-full z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md shadow-sm dark:shadow-none">
    <div
        class="flex {{ $extendedNav ? 'justify-between' : 'flex-wrap justify-between' }} items-center gap-4 px-6 md:px-8 py-4 max-w-7xl mx-auto">
        <a href="{{ route('landing') }}"
            class="font-bold text-teal-900 dark:text-teal-100 font-public-sans tracking-tight inline-flex items-center">
            <img src="{{ asset('img/logo/logo-wide.png') }}" alt="Bantu-In" class="h-9 w-auto object-contain md:h-10"
                width="150" height="40" />
        </a>

        @if ($extendedNav)
            <div class="hidden md:flex items-center gap-8">
                <a class="{{ request()->routeIs('landing') ? 'text-teal-900 dark:text-teal-50 border-b-2 border-teal-800 dark:border-teal-400 pb-1 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-teal-800 dark:hover:text-teal-200' }} transition-all duration-300 ease-in-out"
                    href="{{ route('landing') }}">Beranda</a>
                <a class="{{ request()->routeIs('berita.publik.*') ? 'text-teal-900 dark:text-teal-50 border-b-2 border-teal-800 dark:border-teal-400 pb-1 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-teal-800 dark:hover:text-teal-200' }} transition-colors"
                    href="{{ route('berita.publik.index') }}">Berita</a>
                <a class="text-slate-600 dark:text-slate-400 hover:text-teal-800 dark:hover:text-teal-200 transition-colors"
                    href="{{ url('/#gallery') }}">Galeri</a>
                <a class="text-slate-600 dark:text-slate-400 hover:text-teal-800 dark:hover:text-teal-200 transition-colors"
                    href="{{ url('/#assistance') }}">Alur Bantuan</a>
            </div>
            <div class="flex items-center gap-2 md:gap-4">
                @auth
                    <a href="{{ route('home') }}"
                        class="hidden sm:inline-flex px-4 py-2 text-sm font-semibold text-teal-900 dark:text-teal-100 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 rounded-lg transition-all">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="hidden sm:inline-flex px-4 py-2 text-sm font-semibold text-teal-900 dark:text-teal-100 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 rounded-lg transition-all">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="hidden sm:inline-flex px-4 py-2 text-sm font-semibold bg-primary-container text-white rounded-lg hover:bg-primary transition-all">
                        Daftar
                    </a>
                @endauth
                {{-- <a href="{{ route('login') }}" class="p-2 text-teal-900 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 rounded-lg transition-all" title="Notifikasi" aria-label="Notifikasi">
                    <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                </a>
                <a href="{{ auth()->check() ? route('profile.index') : route('login') }}" class="p-2 text-teal-900 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 rounded-lg transition-all" title="Akun" aria-label="Akun">
                    <span class="material-symbols-outlined" data-icon="account_circle">account_circle</span>
                </a> --}}
            </div>
        @else
            <div class="hidden md:flex items-center gap-8">
                <a class="{{ request()->routeIs('landing') ? 'text-teal-900 dark:text-teal-50 border-b-2 border-teal-800 dark:border-teal-400 pb-1 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-teal-800 dark:hover:text-teal-200' }} transition-colors"
                    href="{{ route('landing') }}">Home</a>
                <a class="{{ request()->routeIs('berita.publik.*') ? 'text-teal-900 dark:text-teal-50 border-b-2 border-teal-800 dark:border-teal-400 pb-1 font-semibold' : 'text-slate-600 dark:text-slate-400 hover:text-teal-800' }} transition-all"
                    href="{{ route('berita.publik.index') }}">Berita</a>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('home') }}"
                        class="px-4 py-2 text-sm font-semibold text-teal-900 dark:text-teal-100 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 rounded-lg transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-sm font-semibold text-teal-900 dark:text-teal-100 hover:bg-slate-50/50 dark:hover:bg-slate-800/50 rounded-lg transition-all">Masuk</a>
                @endauth
            </div>
        @endif
    </div>
</nav>
