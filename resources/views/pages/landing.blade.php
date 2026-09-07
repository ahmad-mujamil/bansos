<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Si-BATUR</title>
    <link rel="icon" href="{{ asset('img/logo-only.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        /* Merah bendera yang diredam — aksen tunggal seluruh situs */
                        "primary": "#c1121f",
                        "primary-container": "#8a0f18",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#f9dcde",
                        "surface-tint": "#c1121f",
                        "inverse-primary": "#f2999f",

                        /* Kertas: putih murni, dihangatkan tipis agar menyatu dengan ilustrasi */
                        "background": "#ffffff",
                        "surface": "#ffffff",
                        "surface-bright": "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#fbf8f5",
                        "surface-container": "#f7f2ee",
                        "surface-container-high": "#f0e9e4",
                        "surface-container-highest": "#e9e0da",
                        "surface-variant": "#f0e9e4",
                        "surface-dim": "#e6dedb",
                        "inverse-surface": "#241c1a",
                        "inverse-on-surface": "#faf6f4",

                        /* Tinta hangat, bukan abu biru */
                        "on-background": "#241c1a",
                        "on-surface": "#241c1a",
                        "on-surface-variant": "#6e625f",
                        "outline": "#8c7f7b",
                        "outline-variant": "#e6dedb",

                        "secondary": "#6e625f",
                        "secondary-container": "#fdedee",
                        "on-secondary": "#ffffff",
                        "on-secondary-container": "#6e625f",

                        "tertiary": "#5a0c12",
                        "tertiary-container": "#8a0f18",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-container": "#f9dcde",

                        "error": "#b3261e",
                        "error-container": "#fdedee",
                        "on-error": "#ffffff",
                        "on-error-container": "#5a0c12",

                        "primary-fixed": "#fdedee",
                        "primary-fixed-dim": "#f2999f",
                        "on-primary-fixed": "#3d0207",
                        "on-primary-fixed-variant": "#8a0f18",
                        "secondary-fixed": "#f7f2ee",
                        "secondary-fixed-dim": "#e9e0da",
                        "on-secondary-fixed": "#241c1a",
                        "on-secondary-fixed-variant": "#6e625f",
                        "tertiary-fixed": "#fdedee",
                        "tertiary-fixed-dim": "#f2999f",
                        "on-tertiary-fixed": "#3d0207",
                        "on-tertiary-fixed-variant": "#8a0f18"
                    },
                    fontFamily: {
                        "jakarta": ["Plus Jakarta Sans", "system-ui", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.375rem",
                        "xl": "0.5rem",
                        "2xl": "0.5rem",
                        "3xl": "0.625rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        /* Judul: satu keluarga, dipadatkan agar berfungsi sebagai elemen visual */
        .display {
            font-weight: 800;
            letter-spacing: -0.035em;
            line-height: 0.98;
        }
        .heading {
            font-weight: 800;
            letter-spacing: -0.028em;
            line-height: 1.1;
        }
        .prose-narrow { max-width: 62ch; }

        /* Veil merah di atas ilustrasi: multiply mewarnai, gradasi menjaga keterbacaan */
        .hero-veil { position: absolute; inset: 0; }
        .hero-veil::before,
        .hero-veil::after {
            content: '';
            position: absolute;
            inset: 0;
        }
        .hero-veil::before {
            background: #a5111c;
            mix-blend-mode: multiply;
            opacity: 0.62;
        }
        .hero-veil::after {
            background: linear-gradient(to right,
                rgba(38, 3, 6, 0.92) 0%,
                rgba(45, 4, 8, 0.72) 38%,
                rgba(58, 6, 11, 0.46) 100%);
        }
        @media (max-width: 767.98px) {
            .hero-veil::after {
                background: linear-gradient(to top,
                    rgba(38, 3, 6, 0.94) 0%,
                    rgba(45, 4, 8, 0.78) 50%,
                    rgba(58, 6, 11, 0.58) 100%);
            }
        }

        /* Kartu berita: garis tipis, tanpa bayangan generik */
        .news-card {
            background: #ffffff;
            border: 1px solid #e6dedb;
            border-radius: 0.5rem;
            overflow: hidden;
            transition: border-color 0.2s ease;
        }
        .news-card:hover { border-color: #c1121f; }
        .news-card-thumb { position: relative; overflow: hidden; background: #f7f2ee; }

        /* Penomoran langkah — sah di sini, isinya memang urutan */
        .alur-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            background: #c1121f;
            color: #ffffff;
            font-weight: 800;
            font-size: 0.95rem;
            font-variant-numeric: tabular-nums;
        }

        /* Fokus keyboard terlihat di seluruh halaman */
        a:focus-visible,
        button:focus-visible {
            outline: 2px solid #c1121f;
            outline-offset: 3px;
            border-radius: 2px;
        }

        /* Satu momen gerak: hero masuk saat halaman dibuka */
        @keyframes hero-rise {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: none; }
        }
        .hero-rise {
            animation: hero-rise 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @media (prefers-reduced-motion: reduce) {
            .hero-rise { animation: none; }
            * { transition-duration: 0.01ms !important; }
        }
    </style>
</head>
<body class="bg-background text-on-background selection:bg-primary-container selection:text-on-primary-container">
    @include('partials.public.header', ['extendedNav' => true])
<main class="pt-20">
    <div class="relative">
        @include('partials.public.hero-slider', ['heroSlides' => $heroSlides])
    </div>
    @include('partials.public.profil-kantor-section')
    <section class="py-20 md:py-28 bg-surface" id="news">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between mb-12 pb-6 border-b border-outline-variant">
                <div class="space-y-3">
                    <h2 class="heading text-3xl md:text-4xl text-on-surface">Berita terbaru</h2>
                    <p class="text-on-surface-variant prose-narrow">Kabar penyaluran dan program bantuan di Lombok Barat.</p>
                </div>
                <a href="{{ route('berita.publik.index') }}"
                   class="shrink-0 inline-flex items-center gap-2 text-primary font-semibold hover:text-primary-container transition-colors">
                    Lihat semua berita
                    <span class="material-symbols-outlined text-lg" data-icon="arrow_forward">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($beritaTerbaru as $item)
                    <a href="{{ route('berita.publik.show', ['berita' => $item->slug]) }}" class="group news-card block text-start">
                        <div class="h-48 news-card-thumb">
                            @php $thumb = $item->getFirstMediaUrl('featured'); @endphp
                            @if($thumb !== '')
                                <img alt="{{ $item->judul }}" class="w-full h-full object-cover" src="{{ $thumb }}"/>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl opacity-30" data-icon="image">image</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-5 space-y-2.5">
                            <div class="flex items-center gap-2 text-xs text-on-surface-variant">
                                <span class="font-semibold text-primary">{{ $item->kategoriBerita?->nama ?? 'Berita' }}</span>
                                <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
                                <span>{{ $item->published_at?->translatedFormat('d F Y') }}</span>
                            </div>
                            <h3 class="text-lg font-bold leading-snug text-on-surface line-clamp-2 group-hover:text-primary transition-colors">{{ $item->judul }}</h3>
                            <p class="text-on-surface-variant text-sm line-clamp-2 leading-relaxed">{{ $item->ringkasan }}</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-lg border border-outline-variant bg-surface-container-low p-10 text-center text-on-surface-variant">
                        Belum ada berita yang terbit.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <section class="py-20 md:py-28 bg-surface-container-low border-y border-outline-variant" id="assistance">
        <div class="max-w-7xl mx-auto px-6 md:px-8">
            <div class="max-w-2xl mb-10 space-y-3">
                <h2 class="heading text-3xl md:text-4xl text-on-surface">Alur bantuan</h2>
                <p class="text-on-surface-variant prose-narrow">
                    Pilih kategori untuk melihat tahapan yang akan Anda lalui, dari pengajuan hingga bantuan diterima.
                </p>
            </div>

            <div class="flex flex-wrap gap-2 mb-12" role="tablist" aria-label="Kategori bantuan">
                @foreach(($alurBantuanPublik ?? collect()) as $kategori)
                    <button
                        type="button"
                        role="tab"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        data-alur-tab-button="{{ $kategori['key'] }}"
                        class="rounded-full px-5 py-2 text-sm font-semibold border transition-colors {{ $loop->first ? 'bg-primary text-white border-primary' : 'bg-surface-container-lowest text-on-surface-variant border-outline-variant hover:border-primary hover:text-primary' }}"
                    >
                        {{ $kategori['label'] }}
                    </button>
                @endforeach
            </div>

            @foreach(($alurBantuanPublik ?? collect()) as $kategori)
                <div data-alur-panel="{{ $kategori['key'] }}" class="{{ $loop->first ? '' : 'hidden' }}">
                    <ol class="grid gap-x-8 gap-y-10 sm:grid-cols-2 lg:grid-cols-{{ min(max(count($kategori['steps']), 1), 4) }}">
                        @foreach($kategori['steps'] as $step)
                            @php
                                $icon = (string) ($step['icon'] ?? 'task_alt');
                                // Nomor sudah jadi elemen struktural — buang prefix "1." / "1)" dari judul
                                $judulLangkah = preg_replace('/^\s*\d+\s*[.)-]\s*/u', '', (string) $step['title']);
                            @endphp
                            <li class="pt-5 border-t-2 border-primary/25">
                                <div class="flex items-center gap-3 mb-4">
                                    <span class="alur-num">{{ $loop->iteration }}</span>
                                    <span class="material-symbols-outlined text-primary/50 text-2xl" data-icon="{{ $icon }}">{{ $icon }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-on-surface leading-snug mb-2">{{ $judulLangkah }}</h3>
                                <p class="text-sm text-on-surface-variant leading-relaxed">{{ $step['description'] }}</p>
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endforeach
        </div>
    </section>
    {{-- ═══ GALERI ═══ --}}
    @php
        $galeriList = $galeriItems->take(9)->values();
        $glItems = $galeriList->map(fn($g) => [
            'src'     => $g->getFirstMediaUrl('galeri'),
            'caption' => $g->keterangan ?: $g->judul,
        ])->values()->toJson();
    @endphp
    <section class="py-20 bg-white" id="gallery">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">

            {{-- Header --}}
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between mb-10 pb-6 border-b border-outline-variant">
                <h2 class="heading text-3xl md:text-4xl text-on-surface">Galeri kegiatan</h2>
                <p class="text-sm text-on-surface-variant max-w-sm md:text-right leading-relaxed">
                    Rekam jejak penyaluran bantuan di desa-desa Lombok Barat.
                </p>
            </div>

            @if($galeriList->isNotEmpty())

                {{-- Top section: featured left + 2 stacked right --}}
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">

                    {{-- Featured --}}
                    <div class="col-span-2 h-56 md:h-[530px] group relative overflow-hidden rounded-2xl bg-surface-container cursor-pointer"
                         onclick="glOpen(0)">
                        @if($galeriList->get(0)?->getFirstMediaUrl('galeri'))
                            <img src="{{ $galeriList->get(0)->getFirstMediaUrl('galeri','thumb') ?: $galeriList->get(0)->getFirstMediaUrl('galeri') }}"
                                 alt="{{ e($galeriList->get(0)->judul) }}"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.04]"/>
                        @endif
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition-colors duration-300 flex items-center justify-center">
                            <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 scale-75 group-hover:scale-100 transition-all duration-300">
                                <span class="material-symbols-outlined text-white text-2xl" data-icon="zoom_in">zoom_in</span>
                            </div>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 p-5 bg-gradient-to-t from-black/55 to-transparent translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                            <p class="text-white text-sm font-medium line-clamp-1">{{ $galeriList->get(0)->keterangan ?: $galeriList->get(0)->judul }}</p>
                        </div>
                    </div>

                    {{-- Right: 2 stacked --}}
                    <div class="hidden md:flex flex-col gap-3" style="height:530px">
                        @foreach($galeriList->slice(1, 2) as $si => $item)
                            <div class="flex-1 group relative overflow-hidden rounded-2xl bg-surface-container cursor-pointer"
                                 onclick="glOpen({{ $si + 1 }})">
                                @if($item->getFirstMediaUrl('galeri'))
                                    <img src="{{ $item->getFirstMediaUrl('galeri','thumb') ?: $item->getFirstMediaUrl('galeri') }}"
                                         alt="{{ e($item->judul) }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.06]"/>
                                @endif
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition-colors duration-300 flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 scale-75 group-hover:scale-100 transition-all duration-300">
                                        <span class="material-symbols-outlined text-white text-xl" data-icon="zoom_in">zoom_in</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Bottom strip: items 3–8 --}}
                @if($galeriList->count() > 3)
                    <div class="grid grid-cols-2 md:grid-cols-{{ min($galeriList->slice(3)->count(), 3) > 2 ? '3' : $galeriList->slice(3)->count() }} gap-3 mt-3">
                        @foreach($galeriList->slice(3, 6) as $si => $item)
                            <div class="h-44 md:h-52 group relative overflow-hidden rounded-2xl bg-surface-container cursor-pointer"
                                 onclick="glOpen({{ $si + 3 }})">
                                @if($item->getFirstMediaUrl('galeri'))
                                    <img src="{{ $item->getFirstMediaUrl('galeri','thumb') ?: $item->getFirstMediaUrl('galeri') }}"
                                         alt="{{ e($item->judul) }}"
                                         class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-[1.06]"/>
                                @endif
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition-colors duration-300 flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 scale-75 group-hover:scale-100 transition-all duration-300">
                                        <span class="material-symbols-outlined text-white text-xl" data-icon="zoom_in">zoom_in</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            @else
                <div class="flex flex-col items-center justify-center py-24 text-on-surface-variant/50 gap-4">
                    <span class="material-symbols-outlined text-6xl" data-icon="photo_library">photo_library</span>
                    <p class="text-sm tracking-wide">Belum ada foto galeri.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- ═══ LIGHTBOX ═══ --}}
    <div id="gl-lb" class="fixed inset-0 z-[999] hidden bg-black/95 backdrop-blur-md" role="dialog" aria-modal="true">
        <button onclick="glClose()" aria-label="Tutup"
                class="absolute top-5 right-5 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-colors text-white">
            <span class="material-symbols-outlined text-xl" data-icon="close">close</span>
        </button>
        <div class="absolute top-5 left-1/2 -translate-x-1/2 text-white/40 text-xs tracking-[0.2em] select-none font-medium" id="gl-counter"></div>
        <button onclick="glNav(-1)" aria-label="Sebelumnya"
                class="absolute left-3 md:left-6 top-1/2 -translate-y-1/2 z-10 w-11 h-11 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-colors text-white">
            <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
        </button>
        <button onclick="glNav(1)" aria-label="Berikutnya"
                class="absolute right-3 md:right-6 top-1/2 -translate-y-1/2 z-10 w-11 h-11 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-colors text-white">
            <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
        </button>
        <div class="flex flex-col items-center justify-center h-full px-16 md:px-24 gap-5">
            <img id="gl-img" src="" alt=""
                 class="max-h-[78vh] max-w-full w-auto object-contain rounded-xl shadow-2xl"/>
            <p id="gl-caption" class="text-white/60 text-sm text-center max-w-lg leading-relaxed"></p>
        </div>
    </div>
    <section class="relative isolate overflow-hidden" id="about">
        <img src="{{ asset('img/background/background-1.jpeg') }}"
             alt="Warga Lombok Barat membawa paket bantuan pangan, perikanan, dan peternakan"
             class="absolute inset-0 -z-20 h-full w-full object-cover object-center"/>
        <div class="absolute inset-0 -z-10 hero-veil" aria-hidden="true"></div>

        <div class="max-w-7xl mx-auto px-6 md:px-8 py-24 md:py-32 grid gap-14 md:grid-cols-12 md:items-end">
            <div class="md:col-span-7 space-y-7">
                <h2 class="display text-4xl md:text-5xl text-white">
                    Bantuan yang sampai ke orang yang tepat.
                </h2>
                <p class="text-lg leading-relaxed text-white/85 prose-narrow">
                    Si-BATUR dibangun BKAD Lombok Barat sebagai ruang kerja bersama antara pemerintah daerah
                    dan masyarakat &mdash; mulai dari pengajuan, verifikasi berjenjang, sampai pemantauan penyaluran.
                </p>
                <div>
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center justify-center rounded-md bg-white px-7 py-3.5 font-bold text-primary transition-colors hover:bg-on-primary-container">
                        Masuk untuk mengajukan bantuan
                    </a>
                </div>
            </div>

            <dl class="md:col-span-5 md:col-start-8 divide-y divide-white/20 border-t border-white/20">
                <div class="flex items-baseline justify-between gap-6 py-4">
                    <dt class="text-white/80">Individu aktif</dt>
                    <dd class="text-3xl font-extrabold tabular-nums text-white">{{ number_format($totalPenggunaIndividuAktif ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="flex items-baseline justify-between gap-6 py-4">
                    <dt class="text-white/80">Kelompok masyarakat</dt>
                    <dd class="text-3xl font-extrabold tabular-nums text-white">{{ number_format($totalKelompokTerdaftar ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="flex items-baseline justify-between gap-6 py-4">
                    <dt class="text-white/80">Organisasi &amp; lembaga</dt>
                    <dd class="text-3xl font-extrabold tabular-nums text-white">{{ number_format($totalOrganisasiTerdaftar ?? 0, 0, ',', '.') }}</dd>
                </div>
                <p class="pt-4 text-sm leading-relaxed text-white/65">
                    Akun perorangan terverifikasi, data kelompok masyarakat, serta organisasi, yayasan,
                    tempat ibadah, dan instansi yang terdaftar di sistem.
                </p>
            </dl>
        </div>
    </section>
</main>
    @include('partials.public.footer', ['variant' => 'full'])
    <script>
        var glData = {!! $glItems !!};
        var glCurrent = 0;

        function glOpen(idx) {
            glCurrent = idx;
            glRender();
            var lb = document.getElementById('gl-lb');
            lb.classList.remove('hidden');
            lb.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        function glClose() {
            var lb = document.getElementById('gl-lb');
            lb.classList.add('hidden');
            lb.classList.remove('flex');
            document.body.style.overflow = '';
        }
        function glNav(dir) {
            glCurrent = (glCurrent + dir + glData.length) % glData.length;
            glRender();
        }
        function glRender() {
            var item = glData[glCurrent];
            var img = document.getElementById('gl-img');
            img.style.opacity = '0';
            img.src = item.src;
            img.onload = function () { img.style.opacity = '1'; };
            document.getElementById('gl-caption').textContent = item.caption;
            document.getElementById('gl-counter').textContent = (glCurrent + 1) + ' / ' + glData.length;
        }
        document.getElementById('gl-lb').addEventListener('click', function (e) {
            if (e.target === this) glClose();
        });
        document.addEventListener('keydown', function (e) {
            var lb = document.getElementById('gl-lb');
            if (lb.classList.contains('hidden')) return;
            if (e.key === 'Escape')      glClose();
            if (e.key === 'ArrowRight')  glNav(1);
            if (e.key === 'ArrowLeft')   glNav(-1);
        });

        (function () {
            var root = document.getElementById('hero-slider-root');
            if (!root) {
                return;
            }
            var slides = root.querySelectorAll('[data-hero-slide]');
            if (slides.length < 2) {
                return;
            }
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }
            var ms = parseInt(root.getAttribute('data-autoplay-ms') || '6500', 10);
            var i = 0;
            setInterval(function () {
                slides[i].classList.remove('opacity-100', 'z-10');
                slides[i].classList.add('opacity-0', 'z-0');
                slides[i].removeAttribute('aria-current');
                i = (i + 1) % slides.length;
                slides[i].classList.remove('opacity-0', 'z-0');
                slides[i].classList.add('opacity-100', 'z-10');
                slides[i].setAttribute('aria-current', 'true');
            }, ms);
        })();

        (function () {
            var buttons = document.querySelectorAll('[data-alur-tab-button]');
            var panels = document.querySelectorAll('[data-alur-panel]');
            if (!buttons.length || !panels.length) {
                return;
            }
            buttons.forEach(function (button) {
                button.addEventListener('click', function () {
                    var key = button.getAttribute('data-alur-tab-button');
                    buttons.forEach(function (otherButton) {
                        otherButton.classList.remove('bg-primary', 'text-white', 'border-primary');
                        otherButton.classList.add('bg-surface-container-lowest', 'text-primary');
                    });
                    button.classList.add('bg-primary', 'text-white', 'border-primary');
                    button.classList.remove('bg-surface-container-lowest');
                    panels.forEach(function (panel) {
                        if (panel.getAttribute('data-alur-panel') === key) {
                            panel.classList.remove('hidden');
                            return;
                        }
                        panel.classList.add('hidden');
                    });
                });
            });
        })();

    </script>
</body>
</html>
