@php
    $extendedNav = $extendedNav ?? false;
    $navLink = 'text-sm text-on-surface-variant hover:text-primary transition-colors';
    $navLinkActive = 'text-sm font-semibold text-primary border-b-2 border-primary pb-1';
@endphp
<nav class="fixed top-0 w-full z-50 bg-white/90 backdrop-blur border-b border-outline-variant">
    <div class="flex items-center justify-between gap-4 px-6 md:px-8 py-4 max-w-7xl mx-auto">
        <a href="{{ route('landing') }}" class="inline-flex items-center shrink-0" aria-label="Si-BATUR, beranda">
            <img src="{{ asset('img/logo/logo-wide.png') }}" alt="Si-BATUR" class="h-9 w-auto object-contain md:h-10"
                width="150" height="40" />
        </a>

        @if ($extendedNav)
            <div class="hidden md:flex items-center gap-7">
                <a class="{{ request()->routeIs('landing') ? $navLinkActive : $navLink }}"
                    href="{{ route('landing') }}">Beranda</a>
                <a class="{{ request()->routeIs('berita.publik.*') ? $navLinkActive : $navLink }}"
                    href="{{ route('berita.publik.index') }}">Berita</a>
                <a class="{{ $navLink }}" href="{{ url('/#profil-kantor') }}">Profil kantor</a>
                <a class="{{ $navLink }}" href="{{ url('/#gallery') }}">Galeri</a>
                <a class="{{ $navLink }}" href="{{ url('/#assistance') }}">Alur bantuan</a>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('home') }}"
                        class="inline-flex px-5 py-2 text-sm font-bold text-white bg-primary rounded-md hover:bg-primary-container transition-colors">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex px-3 py-2 text-sm font-semibold text-on-surface hover:text-primary transition-colors">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="inline-flex px-5 py-2 text-sm font-bold text-white bg-primary rounded-md hover:bg-primary-container transition-colors">
                        Daftar
                    </a>
                @endauth
            </div>
        @else
            <div class="hidden md:flex items-center gap-7">
                <a class="{{ request()->routeIs('landing') ? $navLinkActive : $navLink }}"
                    href="{{ route('landing') }}">Beranda</a>
                <a class="{{ request()->routeIs('berita.publik.*') ? $navLinkActive : $navLink }}"
                    href="{{ route('berita.publik.index') }}">Berita</a>
            </div>
            <div class="flex items-center gap-2">
                @auth
                    <a href="{{ route('home') }}"
                        class="inline-flex px-5 py-2 text-sm font-bold text-white bg-primary rounded-md hover:bg-primary-container transition-colors">Dashboard</a>
                @else
                    <a href="{{ route('login') }}"
                        class="inline-flex px-5 py-2 text-sm font-bold text-white bg-primary rounded-md hover:bg-primary-container transition-colors">Masuk</a>
                @endauth
            </div>
        @endif
    </div>
</nav>
