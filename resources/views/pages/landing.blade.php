<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Si-BATUR</title>
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
                        "on-secondary-container": "#475569",
                        "primary-container": "#2563eb",
                        "inverse-on-surface": "#eff1f1",
                        "on-primary-container": "#bfdbfe",
                        "inverse-primary": "#60a5fa",
                        "inverse-surface": "#1e293b",
                        "on-background": "#0f172a",
                        "surface-container-low": "#f1f5f9",
                        "surface-container-highest": "#e2e8f0",
                        "tertiary-fixed-dim": "#a7ccdb",
                        "tertiary-fixed": "#c3e8f8",
                        "on-tertiary-fixed-variant": "#1e3a8a",
                        "tertiary": "#0c2d6b",
                        "primary-fixed": "#dbeafe",
                        "on-error": "#ffffff",
                        "tertiary-container": "#1e3a8a",
                        "on-secondary": "#ffffff",
                        "surface-container": "#eef2f7",
                        "surface-container-lowest": "#ffffff",
                        "on-primary-fixed-variant": "#1e3a8a",
                        "on-surface": "#0f172a",
                        "secondary": "#475569",
                        "on-surface-variant": "#475569",
                        "on-tertiary": "#ffffff",
                        "primary-fixed-dim": "#60a5fa",
                        "on-secondary-fixed-variant": "#334155",
                        "surface-dim": "#cbd5e1",
                        "surface-tint": "#2563eb",
                        "outline": "#64748b",
                        "surface": "#f8fafc",
                        "on-tertiary-container": "#bfdbfe",
                        "background": "#f8fafc",
                        "error": "#dc2626",
                        "on-tertiary-fixed": "#0c1c5e",
                        "surface-container-high": "#e2e8f0",
                        "on-primary-fixed": "#172554",
                        "on-secondary-fixed": "#1e293b",
                        "outline-variant": "#cbd5e1",
                        "error-container": "#fee2e2",
                        "secondary-container": "#dbeafe",
                        "surface-bright": "#f8fafc",
                        "primary": "#1d4ed8",
                        "secondary-fixed": "#dbeafe",
                        "on-primary": "#ffffff",
                        "secondary-fixed-dim": "#93c5fd",
                        "surface-variant": "#e2e8f0",
                        "on-error-container": "#7f1d1d"
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
            background:
                radial-gradient(circle at 80% 20%, rgba(59,130,246,0.45) 0, transparent 45%),
                radial-gradient(circle at 15% 85%, rgba(99,102,241,0.40) 0, transparent 50%),
                linear-gradient(135deg, rgba(15,23,42,0.85) 0%, rgba(30,64,175,0.85) 55%, rgba(29,78,216,0.85) 100%);
        }

        /* === Floating Particles (hero & sections) === */
        .particles-layer {
            position: absolute;
            inset: 0;
            pointer-events: none;
            overflow: hidden;
            z-index: 1;
        }
        .lp-particle {
            position: absolute;
            bottom: -60px;
            display: block;
            border-radius: 50%;
            background: rgba(255,255,255,0.7);
            box-shadow: 0 0 14px rgba(255,255,255,0.55);
            animation: lp-float linear infinite;
            opacity: 0;
            will-change: transform, opacity;
        }
        @keyframes lp-float {
            0%   { transform: translate3d(0, 0, 0) scale(0.6); opacity: 0; }
            10%  { opacity: 0.9; }
            50%  { transform: translate3d(24px, -50vh, 0) scale(1); opacity: 0.85; }
            90%  { opacity: 0.5; }
            100% { transform: translate3d(-14px, -110vh, 0) scale(0.4); opacity: 0; }
        }
        .lp-p1  { left:  4%; width:  6px; height:  6px; animation-duration: 16s; animation-delay:  0s; }
        .lp-p2  { left:  9%; width: 10px; height: 10px; animation-duration: 20s; animation-delay:  2s; background: rgba(147,197,253,0.75); box-shadow: 0 0 14px rgba(147,197,253,0.6); }
        .lp-p3  { left: 14%; width:  4px; height:  4px; animation-duration: 12s; animation-delay:  4s; }
        .lp-p4  { left: 19%; width:  8px; height:  8px; animation-duration: 18s; animation-delay:  1s; background: rgba(125,211,252,0.75); box-shadow: 0 0 14px rgba(125,211,252,0.55); }
        .lp-p5  { left: 26%; width: 12px; height: 12px; animation-duration: 24s; animation-delay:  6s; }
        .lp-p6  { left: 33%; width:  5px; height:  5px; animation-duration: 14s; animation-delay:  3s; }
        .lp-p7  { left: 40%; width:  9px; height:  9px; animation-duration: 21s; animation-delay:  5s; background: rgba(34,211,238,0.7); box-shadow: 0 0 14px rgba(34,211,238,0.5); }
        .lp-p8  { left: 47%; width:  6px; height:  6px; animation-duration: 16s; animation-delay:  7s; }
        .lp-p9  { left: 54%; width: 11px; height: 11px; animation-duration: 26s; animation-delay:  0s; background: rgba(255,255,255,0.5); }
        .lp-p10 { left: 61%; width:  4px; height:  4px; animation-duration: 13s; animation-delay:  9s; }
        .lp-p11 { left: 68%; width:  8px; height:  8px; animation-duration: 19s; animation-delay:  2s; background: rgba(167,139,250,0.7); box-shadow: 0 0 14px rgba(167,139,250,0.55); }
        .lp-p12 { left: 74%; width:  6px; height:  6px; animation-duration: 15s; animation-delay:  6s; }
        .lp-p13 { left: 80%; width: 10px; height: 10px; animation-duration: 23s; animation-delay:  4s; background: rgba(147,197,253,0.75); box-shadow: 0 0 14px rgba(147,197,253,0.55); }
        .lp-p14 { left: 86%; width:  5px; height:  5px; animation-duration: 14s; animation-delay:  8s; }
        .lp-p15 { left: 92%; width:  9px; height:  9px; animation-duration: 20s; animation-delay:  1s; }
        .lp-p16 { left: 96%; width:  6px; height:  6px; animation-duration: 17s; animation-delay:  5s; background: rgba(255,255,255,0.55); }

        /* === Decorative blobs === */
        .deco-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.55;
            pointer-events: none;
            z-index: 0;
        }
        .deco-blob-blue   { background: radial-gradient(circle, #60a5fa 0%, rgba(96,165,250,0) 70%); }
        .deco-blob-violet { background: radial-gradient(circle, #a78bfa 0%, rgba(167,139,250,0) 70%); }
        .deco-blob-cyan   { background: radial-gradient(circle, #22d3ee 0%, rgba(34,211,238,0) 70%); }

        /* === Reveal on scroll === */
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.7s ease, transform 0.7s ease;
            will-change: opacity, transform;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* === Berita card === */
        .news-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            overflow: hidden;
        }
        .news-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 24px 50px rgba(15,23,42,0.10);
            border-color: #93c5fd;
        }
        .news-card-thumb { position: relative; overflow: hidden; }
        .news-card-thumb::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(15,23,42,0.45) 0%, transparent 50%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .news-card:hover .news-card-thumb::after { opacity: 1; }
        .news-card-tag {
            display: inline-flex; align-items: center;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #ffffff;
            font-size: 0.68rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* === Alur Bantuan step === */
        .alur-step-icon {
            position: relative;
            transition: transform 0.3s ease;
        }
        .alur-step-icon::before {
            content: '';
            position: absolute;
            inset: -10px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(59,130,246,0.25), rgba(167,139,250,0.25));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
            filter: blur(8px);
        }
        .alur-step:hover .alur-step-icon { transform: scale(1.08); }
        .alur-step:hover .alur-step-icon::before { opacity: 1; }

        /* === Stat number animation pulse === */
        .stat-number {
            background: linear-gradient(135deg, #ffffff 0%, #bfdbfe 100%);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* === Section CTA === */
        .cta-btn {
            position: relative;
            overflow: hidden;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .cta-btn:hover { transform: translateY(-2px); box-shadow: 0 18px 35px rgba(59,130,246,0.45); }
        .cta-btn::after {
            content: '';
            position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.3) 0%, transparent 60%);
            opacity: 0; transition: opacity 0.25s ease;
        }
        .cta-btn:hover::after { opacity: 1; }

        @media (prefers-reduced-motion: reduce) {
            .lp-particle, .reveal { animation: none; transition: none; opacity: 1; transform: none; }
        }
    </style>
</head>
<body class="bg-background text-on-background selection:bg-primary-container selection:text-on-primary-container">
    @include('partials.public.header', ['extendedNav' => true])
<main class="pt-20">
    <div class="relative">
        @include('partials.public.hero-slider', ['heroSlides' => $heroSlides])
        <div class="particles-layer pointer-events-none" style="position: absolute; inset: 0;">
            <span class="lp-particle lp-p1"></span>
            <span class="lp-particle lp-p2"></span>
            <span class="lp-particle lp-p3"></span>
            <span class="lp-particle lp-p4"></span>
            <span class="lp-particle lp-p5"></span>
            <span class="lp-particle lp-p6"></span>
            <span class="lp-particle lp-p7"></span>
            <span class="lp-particle lp-p8"></span>
            <span class="lp-particle lp-p9"></span>
            <span class="lp-particle lp-p10"></span>
            <span class="lp-particle lp-p11"></span>
            <span class="lp-particle lp-p12"></span>
            <span class="lp-particle lp-p13"></span>
            <span class="lp-particle lp-p14"></span>
            <span class="lp-particle lp-p15"></span>
            <span class="lp-particle lp-p16"></span>
        </div>
    </div>
    @include('partials.public.profil-kantor-section')
    <section class="py-24 bg-surface relative overflow-hidden" id="news">
        <div class="deco-blob deco-blob-blue" style="width: 380px; height: 380px; top: -120px; right: -80px; opacity: 0.18;"></div>
        <div class="deco-blob deco-blob-violet" style="width: 320px; height: 320px; bottom: -100px; left: -60px; opacity: 0.15;"></div>
        <div class="max-w-7xl mx-auto px-8 relative z-10">
            <div class="flex justify-between items-end mb-12 reveal">
                <div class="space-y-2">
                    <p class="text-xs font-bold tracking-[0.18em] uppercase text-primary/60">Informasi Terkini</p>
                    <h2 class="text-4xl font-bold text-primary">Berita Terbaru</h2>
                    <p class="text-on-surface-variant">Update terkini mengenai penyaluran dan program bantuan sosial.</p>
                </div>
                <a href="{{ route('berita.publik.index') }}" class="text-primary font-bold flex items-center gap-2 hover:gap-3 transition-all group">
                    Lihat Semua
                    <span class="w-9 h-9 rounded-full bg-primary text-white flex items-center justify-center group-hover:bg-primary-container transition-colors">
                        <span class="material-symbols-outlined text-base" data-icon="arrow_forward">arrow_forward</span>
                    </span>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @forelse($beritaTerbaru as $item)
                    <a href="{{ route('berita.publik.show', ['berita' => $item->slug]) }}" class="group news-card block text-start reveal" style="transition-delay: {{ $loop->index * 80 }}ms;">
                        <div class="h-52 news-card-thumb bg-surface-container">
                            @php $thumb = $item->getFirstMediaUrl('featured'); @endphp
                            @if($thumb !== '')
                                <img alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="{{ $thumb }}"/>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-on-surface-variant text-sm">
                                    <span class="material-symbols-outlined text-5xl opacity-40" data-icon="image">image</span>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3 z-10">
                                <span class="news-card-tag">{{ $item->kategoriBerita?->nama ?? 'Berita' }}</span>
                            </div>
                        </div>
                        <div class="p-6 space-y-3">
                            <h3 class="text-xl font-bold text-primary line-clamp-2 group-hover:text-primary-container transition-colors">{{ $item->judul }}</h3>
                            <p class="text-on-surface-variant text-sm line-clamp-2 leading-relaxed">{{ $item->ringkasan }}</p>
                            <div class="pt-3 flex items-center justify-between text-xs">
                                <span class="flex items-center text-on-surface-variant gap-1">
                                    <span class="material-symbols-outlined text-sm" data-icon="calendar_today">calendar_today</span>
                                    {{ $item->published_at?->translatedFormat('d M Y') }}
                                </span>
                                <span class="text-primary font-semibold flex items-center gap-1 group-hover:gap-2 transition-all">
                                    Baca <span class="material-symbols-outlined text-sm" data-icon="arrow_forward">arrow_forward</span>
                                </span>
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
    <section class="py-24 bg-surface-container relative overflow-hidden" id="assistance">
        <div class="deco-blob deco-blob-cyan" style="width: 360px; height: 360px; top: 10%; right: -100px; opacity: 0.18;"></div>
        <div class="deco-blob deco-blob-violet" style="width: 280px; height: 280px; bottom: 5%; left: -80px; opacity: 0.20;"></div>
        <div class="max-w-7xl mx-auto px-8 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-4 reveal">
                <p class="text-xs font-bold tracking-[0.18em] uppercase text-primary/60">Bagaimana Caranya?</p>
                <h2 class="text-4xl font-bold text-primary">Alur Bantuan</h2>
                <p class="text-on-surface-variant text-lg">Alur per kategori bantuan yang dikelola dari panel admin untuk memastikan informasi selalu terbaru.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-3 mb-12 reveal">
                @foreach(($alurBantuanPublik ?? collect()) as $kategori)
                    <button
                        type="button"
                        data-alur-tab-button="{{ $kategori['key'] }}"
                        class="rounded-full px-6 py-2.5 text-sm font-semibold border transition-all shadow-sm hover:-translate-y-0.5 {{ $loop->first ? 'bg-primary text-white border-primary shadow-md' : 'bg-surface-container-lowest text-primary border-outline-variant hover:bg-primary hover:text-white hover:border-primary' }}"
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
                                $isOdd = $loop->odd;
                            @endphp
                            <div class="alur-step relative z-10 flex-none w-full md:w-64 flex flex-col items-center text-center space-y-5 reveal" style="transition-delay: {{ $loop->index * 100 }}ms;">
                                <div class="alur-step-icon w-24 h-24 rounded-full flex items-center justify-center shadow-xl ring-8 ring-surface-container relative"
                                     style="background: linear-gradient(135deg, {{ $isOdd ? '#3b82f6 0%, #1d4ed8 100%' : '#60a5fa 0%, #2563eb 100%' }}); color: #ffffff;">
                                    <span class="material-symbols-outlined text-4xl relative z-10" data-icon="{{ $icon }}">{{ $icon }}</span>
                                    <span class="absolute -top-2 -right-2 w-9 h-9 rounded-full bg-white text-primary font-bold text-sm flex items-center justify-center shadow-md ring-2 ring-primary/20">{{ $loop->iteration }}</span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-primary text-lg mb-2">{{ $step['title'] }}</h4>
                                    <p class="text-sm text-on-surface-variant leading-relaxed px-4">{{ $step['description'] }}</p>
                                </div>
                            </div>
                            @unless($loop->last)
                                <div class="hidden md:flex flex-none w-12 lg:w-20 items-start justify-center pt-12" aria-hidden="true">
                                    <div class="w-full border-t-2 border-dashed border-primary/30 relative">
                                        <span class="absolute right-0 top-1/2 -translate-y-1/2 w-2 h-2 bg-primary/40 rounded-full"></span>
                                    </div>
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
    <section class="py-24 bg-primary overflow-hidden relative">
        <div class="absolute inset-0 opacity-30" style="background:
            radial-gradient(circle at 20% 30%, rgba(96,165,250,0.5) 0, transparent 40%),
            radial-gradient(circle at 80% 70%, rgba(167,139,250,0.4) 0, transparent 45%);"></div>
        <div class="particles-layer">
            <span class="lp-particle lp-p1"></span>
            <span class="lp-particle lp-p2"></span>
            <span class="lp-particle lp-p3"></span>
            <span class="lp-particle lp-p4"></span>
            <span class="lp-particle lp-p5"></span>
            <span class="lp-particle lp-p6"></span>
            <span class="lp-particle lp-p7"></span>
            <span class="lp-particle lp-p8"></span>
            <span class="lp-particle lp-p9"></span>
            <span class="lp-particle lp-p10"></span>
            <span class="lp-particle lp-p11"></span>
            <span class="lp-particle lp-p12"></span>
            <span class="lp-particle lp-p13"></span>
            <span class="lp-particle lp-p14"></span>
            <span class="lp-particle lp-p15"></span>
            <span class="lp-particle lp-p16"></span>
        </div>
        <div class="max-w-7xl mx-auto px-8 grid md:grid-cols-2 gap-20 items-center relative z-10">
            <div class="relative reveal">
                <div class="absolute -top-12 -left-12 w-64 h-64 bg-primary-container/30 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-8 -right-8 w-48 h-48 bg-cyan-400/20 rounded-full blur-3xl"></div>
                <div class="relative z-10 bg-white/8 backdrop-blur-xl border border-white/15 p-10 rounded-3xl shadow-2xl hover:bg-white/10 transition-colors">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/15 mb-5">
                        <span class="material-symbols-outlined text-base text-on-primary-container" data-icon="verified">verified</span>
                        <span class="text-xs font-bold tracking-widest uppercase text-on-primary-container">Resmi BKAD</span>
                    </div>
                    <h2 class="text-3xl font-bold text-white mb-6">Tentang Si-BATUR</h2>
                    <p class="text-on-primary-container text-lg leading-relaxed mb-8">
                        "Si-BATUR merupakan aplikasi yang dibuat oleh BKAD Lombok Barat untuk mendukung pengelolaan dan penyaluran bantuan sosial yang tepat sasaran, transparan, dan akuntabel."
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
            <div class="space-y-8 reveal">
                <h2 class="text-4xl md:text-5xl font-bold text-white tracking-tight leading-tight">
                    Layanan Bansos Terintegrasi untuk <span class="stat-number">Masyarakat Lombok Barat.</span>
                </h2>
                <p class="text-on-primary-container text-xl font-light">
                    Si-BATUR menjadi media kolaborasi antara Pemerintah Kabupaten Lombok Barat dan masyarakat dalam proses pengajuan, verifikasi, hingga pemantauan bantuan sosial.
                </p>
                <div class="flex flex-wrap gap-6 md:gap-8 border-t border-white/15 pt-10 justify-center md:justify-start">
                    <div class="text-center md:text-left min-w-[9rem] p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:bg-white/10 transition-colors">
                        <div class="text-3xl md:text-4xl font-extrabold stat-number tabular-nums">{{ number_format($totalPenggunaIndividuAktif ?? 0, 0, ',', '.') }}</div>
                        <div class="text-on-primary-container text-xs uppercase tracking-widest mt-2 font-semibold">Individu aktif</div>
                    </div>
                    <div class="text-center md:text-left min-w-[9rem] p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:bg-white/10 transition-colors">
                        <div class="text-3xl md:text-4xl font-extrabold stat-number tabular-nums">{{ number_format($totalKelompokTerdaftar ?? 0, 0, ',', '.') }}</div>
                        <div class="text-on-primary-container text-xs uppercase tracking-widest mt-2 font-semibold">Kelompok masyarakat</div>
                    </div>
                    <div class="text-center md:text-left min-w-[9rem] p-4 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-sm hover:bg-white/10 transition-colors">
                        <div class="text-3xl md:text-4xl font-extrabold stat-number tabular-nums">{{ number_format($totalOrganisasiTerdaftar ?? 0, 0, ',', '.') }}</div>
                        <div class="text-on-primary-container text-xs uppercase tracking-widest mt-2 font-semibold">Organisasi &amp; lembaga</div>
                    </div>
                </div>
                <p class="text-on-primary-container/80 text-sm max-w-xl">
                    Ringkasan entitas terdaftar dan aktif di sistem (individu: akun perorangan terverifikasi; kelompok: data kelompok masyarakat; organisasi &amp; lembaga: organisasi, yayasan, tempat ibadah, dan instansi).
                </p>
                <div class="pt-2">
                    <a href="{{ route('login') }}" class="cta-btn inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl font-semibold text-white shadow-lg" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 55%, #1d4ed8 100%);">
                        <span class="material-symbols-outlined text-lg" data-icon="rocket_launch">rocket_launch</span>
                        Mulai ajukan atau pantau bantuan
                        <span class="material-symbols-outlined text-lg" data-icon="arrow_forward">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</main>
    @include('partials.public.footer', ['variant' => 'full'])
    <script src="{{ asset('/js/particles-futuristic.js') }}"></script>
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

        /* === Reveal on scroll === */
        (function () {
            var els = document.querySelectorAll('.reveal');
            if (!els.length) return;
            if (!('IntersectionObserver' in window)) {
                els.forEach(function (el) { el.classList.add('visible'); });
                return;
            }
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' });
            els.forEach(function (el) { io.observe(el); });
        })();
    </script>
</body>
</html>
