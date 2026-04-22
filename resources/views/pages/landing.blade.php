<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bantu-In</title>
    <link rel="icon" href="{{ asset('img/logo-only.png') }}" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-secondary-container": "#566565",
                        "primary-container": "#005353",
                        "inverse-on-surface": "#eff1f1",
                        "on-primary-container": "#84c5c4",
                        "inverse-primary": "#91d2d1",
                        "inverse-surface": "#2e3131",
                        "on-background": "#191c1d",
                        "surface-container-low": "#f2f4f4",
                        "surface-container-highest": "#e1e3e3",
                        "tertiary-fixed-dim": "#a7ccdb",
                        "tertiary-fixed": "#c3e8f8",
                        "on-tertiary-fixed-variant": "#274c58",
                        "tertiary": "#103844",
                        "primary-fixed": "#adeeed",
                        "on-error": "#ffffff",
                        "tertiary-container": "#2a4f5b",
                        "on-secondary": "#ffffff",
                        "surface-container": "#eceeee",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-fixed-variant": "#004f4f",
                        "on-surface": "#191c1d",
                        "secondary": "#526161",
                        "on-surface-variant": "#3f4948",
                        "on-tertiary": "#ffffff",
                        "primary-fixed-dim": "#91d2d1",
                        "on-secondary-fixed-variant": "#3b494a",
                        "surface-dim": "#d8dada",
                        "surface-tint": "#246868",
                        "outline": "#6f7978",
                        "surface": "#f8fafa",
                        "on-tertiary-container": "#9ac0ce",
                        "background": "#f8fafa",
                        "error": "#ba1a1a",
                        "on-tertiary-fixed": "#001f28",
                        "surface-container-high": "#e6e8e8",
                        "on-primary-fixed": "#002020",
                        "on-secondary-fixed": "#101e1e",
                        "outline-variant": "#bfc8c8",
                        "error-container": "#ffdad6",
                        "secondary-container": "#d3e3e2",
                        "surface-bright": "#f8fafa",
                        "primary": "#003a3a",
                        "secondary-fixed": "#d6e6e5",
                        "on-primary": "#ffffff",
                        "secondary-fixed-dim": "#bacac9",
                        "surface-variant": "#e1e3e3",
                        "on-error-container": "#93000a"
                    },
                    fontFamily: {
                        "headline": ["Public Sans", "sans-serif"],
                        "body": ["Public Sans", "sans-serif"],
                        "label": ["Public Sans", "sans-serif"],
                        "public-sans": ["Public Sans", "sans-serif"],
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(0, 58, 58, 0.95) 0%, rgba(0, 83, 83, 0.8) 100%);
        }
    </style>
</head>
<body class="bg-background text-on-background selection:bg-primary-container selection:text-on-primary-container">
    @include('partials.public.header', ['extendedNav' => true])
<main class="pt-20">
    @include('partials.public.hero-slider', ['heroSlides' => $heroSlides])
    @include('partials.public.profil-kantor-section')
    <section class="py-24 bg-surface" id="news">
        <div class="max-w-7xl mx-auto px-8">
            <div class="flex justify-between items-end mb-12">
                <div class="space-y-2">
                    <h2 class="text-4xl font-bold text-primary">Berita Terbaru</h2>
                    <p class="text-on-surface-variant">Update terkini mengenai penyaluran dan program bantuan sosial.</p>
                </div>
                <a href="{{ route('berita.publik.index') }}" class="text-primary font-bold flex items-center gap-2 hover:underline">
                    Lihat Semua <span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($beritaTerbaru as $item)
                    <a href="{{ route('berita.publik.show', ['berita' => $item->slug]) }}" class="group bg-surface-container-lowest rounded-xl overflow-hidden hover:bg-surface-dim transition-all duration-300 block text-start">
                        <div class="h-48 overflow-hidden bg-surface-container">
                            @php $thumb = $item->getFirstMediaUrl('featured'); @endphp
                            @if($thumb !== '')
                                <img alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ $thumb }}"/>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant text-sm">Tanpa gambar</div>
                            @endif
                        </div>
                        <div class="p-6 space-y-3">
                            <span class="text-xs font-bold text-primary-container tracking-widest uppercase">{{ $item->kategoriBerita?->nama ?? '—' }}</span>
                            <h3 class="text-xl font-bold text-primary line-clamp-2">{{ $item->judul }}</h3>
                            <p class="text-on-surface-variant text-sm line-clamp-2">{{ $item->ringkasan }}</p>
                            <div class="pt-4 flex items-center text-slate-400 text-xs">
                                <span class="material-symbols-outlined text-sm mr-1" data-icon="calendar_today">calendar_today</span>
                                {{ $item->published_at?->translatedFormat('d M Y') }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full rounded-xl border border-outline-variant bg-surface-container-low p-8 text-center text-on-surface-variant">
                        Belum ada berita terbit.
                    </div>
                @endforelse
            </div>
        </div>
    </section>
    <section class="py-24 bg-surface-container" id="assistance">
        <div class="max-w-7xl mx-auto px-8">
            <div class="text-center max-w-3xl mx-auto mb-20 space-y-4">
                <h2 class="text-4xl font-bold text-primary">Alur Bantuan</h2>
                <p class="text-on-surface-variant text-lg">Alur per kategori bantuan yang dikelola dari panel admin untuk memastikan informasi selalu terbaru.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-3 mb-10">
                @foreach(($alurBantuanPublik ?? collect()) as $kategori)
                    <button
                        type="button"
                        data-alur-tab-button="{{ $kategori['key'] }}"
                        class="rounded-full px-5 py-2 text-sm font-semibold border border-outline-variant transition-all {{ $loop->first ? 'bg-primary text-white border-primary' : 'bg-surface-container-lowest text-primary hover:bg-surface-container' }}"
                    >
                        {{ $kategori['label'] }}
                    </button>
                @endforeach
            </div>
            @foreach(($alurBantuanPublik ?? collect()) as $kategori)
                <div data-alur-panel="{{ $kategori['key'] }}" class="{{ $loop->first ? '' : 'hidden' }}">
                    <div class="flex flex-col md:flex-row md:flex-nowrap md:items-start gap-10 md:gap-0 overflow-x-auto md:overflow-x-visible">
                        @foreach($kategori['steps'] as $step)
                            @php
                                $icon = (string) ($step['icon'] ?? 'task_alt');
                            @endphp
                            <div class="relative z-10 flex-none w-full md:w-64 flex flex-col items-center text-center space-y-6">
                                <div class="w-24 h-24 {{ $loop->odd ? 'bg-primary' : 'bg-primary-container' }} text-white rounded-full flex items-center justify-center shadow-xl ring-8 ring-surface-container">
                                    <span class="material-symbols-outlined text-4xl" data-icon="{{ $icon }}">{{ $icon }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-primary text-lg mb-2">{{ $step['title'] }}</h4>
                                    <p class="text-sm text-on-surface-variant leading-relaxed px-4">{{ $step['description'] }}</p>
                                </div>
                            </div>
                            @unless($loop->last)
                                <div class="hidden md:flex flex-none w-12 lg:w-20 items-start justify-center pt-12" aria-hidden="true">
                                    <div class="w-full border-t-2 border-dashed border-outline-variant"></div>
                                </div>
                            @endunless
                        @endforeach
                    </div>
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
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-12">
                <div>
                    <p class="text-xs font-bold tracking-[0.18em] uppercase text-primary/50 mb-2">Dokumentasi</p>
                    <h2 class="text-3xl md:text-4xl font-bold text-primary leading-tight">Galeri Kegiatan</h2>
                </div>
                <p class="text-sm text-on-surface-variant max-w-xs md:text-right leading-relaxed">
                    Rekam jejak kegiatan penyaluran bantuan sosial di Lombok Barat.
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
    <section class="py-24 bg-primary overflow-hidden">
        <div class="max-w-7xl mx-auto px-8 grid md:grid-cols-2 gap-20 items-center">
            <div class="relative">
                <div class="absolute -top-12 -left-12 w-64 h-64 bg-primary-container/20 rounded-full blur-3xl"></div>
                <div class="relative z-10 bg-white/5 backdrop-blur-xl border border-white/10 p-10 rounded-3xl shadow-2xl">
                    <h2 class="text-3xl font-bold text-white mb-6">Tentang Bantu-In</h2>
                    <p class="text-on-primary-container text-lg leading-relaxed mb-8">
                        "Bantu-In merupakan aplikasi yang dibuat oleh BKAD Lombok Barat untuk mendukung pengelolaan dan penyaluran bantuan sosial yang tepat sasaran, transparan, dan akuntabel."
                    </p>
                    {{-- <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-on-primary-container">
                                <span class="material-symbols-outlined" data-icon="verified">verified</span>
                            </div>
                            <div>
                                <h5 class="text-white font-bold">Dikembangkan BKAD Lombok Barat</h5>
                                <p class="text-slate-400 text-sm">Inisiatif digital pemerintah daerah untuk meningkatkan kualitas layanan bantuan sosial.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-on-primary-container">
                                <span class="material-symbols-outlined" data-icon="visibility">visibility</span>
                            </div>
                            <div>
                                <h5 class="text-white font-bold">Informasi Resmi BKAD</h5>
                                <p class="text-slate-400 text-sm">
                                    Kunjungi situs resmi:
                                    <a
                                        href="https://bpkad.lombokbaratkab.go.id/"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-white underline decoration-white/60 underline-offset-4 hover:decoration-white"
                                    >
                                        bpkad.lombokbaratkab.go.id
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
            <div class="space-y-8">
                <h2 class="text-4xl font-bold text-white tracking-tight">Layanan Bansos Terintegrasi untuk Masyarakat Lombok Barat.</h2>
                <p class="text-on-primary-container text-xl font-light">
                    Bantu-In menjadi media kolaborasi antara Pemerintah Kabupaten Lombok Barat dan masyarakat dalam proses pengajuan, verifikasi, hingga pemantauan bantuan sosial.
                </p>
                <div class="flex flex-wrap gap-8 md:gap-12 border-t border-white/10 pt-12 justify-center md:justify-start">
                    <div class="text-center md:text-left min-w-[8rem]">
                        <div class="text-4xl font-extrabold text-white tabular-nums">{{ number_format($totalPenggunaIndividuAktif ?? 0, 0, ',', '.') }}</div>
                        <div class="text-on-primary-container text-sm uppercase tracking-widest mt-2">Individu aktif</div>
                    </div>
                    <div class="text-center md:text-left min-w-[8rem]">
                        <div class="text-4xl font-extrabold text-white tabular-nums">{{ number_format($totalKelompokTerdaftar ?? 0, 0, ',', '.') }}</div>
                        <div class="text-on-primary-container text-sm uppercase tracking-widest mt-2">Kelompok masyarakat</div>
                    </div>
                    <div class="text-center md:text-left min-w-[8rem]">
                        <div class="text-4xl font-extrabold text-white tabular-nums">{{ number_format($totalOrganisasiTerdaftar ?? 0, 0, ',', '.') }}</div>
                        <div class="text-on-primary-container text-sm uppercase tracking-widest mt-2">Organisasi &amp; lembaga</div>
                    </div>
                </div>
                <p class="text-on-primary-container/80 text-sm max-w-xl">
                    Ringkasan entitas terdaftar dan aktif di sistem (individu: akun perorangan terverifikasi; kelompok: data kelompok masyarakat; organisasi &amp; lembaga: organisasi, yayasan, tempat ibadah, dan instansi).
                </p>
                <div class="pt-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-4 bg-primary-container text-white rounded-xl font-semibold hover:bg-white/10 transition-all">
                        Mulai ajukan atau pantau bantuan
                    </a>
                </div>
            </div>
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
