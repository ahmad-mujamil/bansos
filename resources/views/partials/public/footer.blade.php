@php
    $variant = $variant ?? 'full';
@endphp

@if($variant === 'minimal')
    <footer class="border-t border-outline-variant py-8 px-6 text-center text-sm text-on-surface-variant">
        &copy; {{ date('Y') }} Si-BATUR &mdash; Pemerintah Kabupaten Lombok Barat
    </footer>
@else
    <footer class="w-full bg-surface-container-low border-t border-outline-variant">
        <div class="max-w-7xl mx-auto px-6 md:px-8 py-14">
            <div class="flex flex-col gap-10 md:flex-row md:justify-between">
                <div class="max-w-sm space-y-3">
                    <div class="text-lg font-extrabold tracking-tight text-primary">Si-BATUR</div>
                    <p class="text-sm leading-relaxed text-on-surface-variant">
                        Sistem Informasi Bantuan Terpadu dan Terukur, dikelola BKAD Pemerintah
                        Kabupaten Lombok Barat.
                    </p>
                </div>
                <nav class="grid grid-cols-2 gap-x-12 gap-y-3 sm:flex sm:gap-10" aria-label="Tautan footer">
                    <a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ route('berita.publik.index') }}">Berita</a>
                    <a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ url('/#profil-kantor') }}">Profil kantor</a>
                    <a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ url('/#assistance') }}">Alur bantuan</a>
                    <a class="text-sm text-on-surface-variant hover:text-primary transition-colors" href="{{ url('/#gallery') }}">Galeri</a>
                </nav>
            </div>
            <p class="mt-10 pt-6 border-t border-outline-variant text-sm text-on-surface-variant">
                &copy; {{ date('Y') }} Pemerintah Kabupaten Lombok Barat
            </p>
        </div>
    </footer>
@endif
