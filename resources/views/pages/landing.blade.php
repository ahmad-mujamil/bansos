<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bantu-In | Portal Bantuan Sosial Resmi</title>
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
                <p class="text-on-surface-variant text-lg">Proses transparan dan mudah untuk mendapatkan dukungan sosial dari pemerintah.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 relative">
                <div class="hidden md:block absolute top-12 left-[12%] right-[12%] h-0.5 border-t-2 border-dashed border-outline-variant"></div>
                <div class="relative z-10 flex flex-col items-center text-center space-y-6">
                    <div class="w-24 h-24 bg-primary text-white rounded-full flex items-center justify-center shadow-xl ring-8 ring-surface-container">
                        <span class="material-symbols-outlined text-4xl" data-icon="person_search">person_search</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary text-lg mb-2">1. Pendataan</h4>
                        <p class="text-sm text-on-surface-variant leading-relaxed px-4">Petugas melakukan verifikasi data lapangan di lingkungan RT/RW setempat.</p>
                    </div>
                </div>
                <div class="relative z-10 flex flex-col items-center text-center space-y-6">
                    <div class="w-24 h-24 bg-primary-container text-white rounded-full flex items-center justify-center shadow-xl ring-8 ring-surface-container">
                        <span class="material-symbols-outlined text-4xl" data-icon="fact_check">fact_check</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary text-lg mb-2">2. Verifikasi</h4>
                        <p class="text-sm text-on-surface-variant leading-relaxed px-4">Sistem Bantu-In memvalidasi data dengan basis data kependudukan nasional.</p>
                    </div>
                </div>
                <div class="relative z-10 flex flex-col items-center text-center space-y-6">
                    <div class="w-24 h-24 bg-primary text-white rounded-full flex items-center justify-center shadow-xl ring-8 ring-surface-container">
                        <span class="material-symbols-outlined text-4xl" data-icon="notifications_active">notifications_active</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary text-lg mb-2">3. Penetapan</h4>
                        <p class="text-sm text-on-surface-variant leading-relaxed px-4">Pengumuman daftar penerima manfaat melalui portal dan aplikasi seluler.</p>
                    </div>
                </div>
                <div class="relative z-10 flex flex-col items-center text-center space-y-6">
                    <div class="w-24 h-24 bg-primary-container text-white rounded-full flex items-center justify-center shadow-xl ring-8 ring-surface-container">
                        <span class="material-symbols-outlined text-4xl" data-icon="payments">payments</span>
                    </div>
                    <div>
                        <h4 class="font-bold text-primary text-lg mb-2">4. Penyaluran</h4>
                        <p class="text-sm text-on-surface-variant leading-relaxed px-4">Bantuan disalurkan langsung melalui rekening bank atau kantor pos terdekat.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-24 bg-surface-container-lowest" id="gallery">
        <div class="max-w-7xl mx-auto px-8">
            <div class="flex flex-col md:flex-row items-center justify-between mb-16 gap-6">
                <h2 class="text-4xl font-bold text-primary">Galeri Kegiatan</h2>
                <div class="flex gap-2">
                    <span class="px-4 py-2 bg-primary text-white rounded-full text-sm font-medium">Semua</span>
                    <span class="px-4 py-2 bg-surface-container text-on-surface-variant rounded-full text-sm font-medium hover:bg-surface-container-high cursor-pointer">Logistik</span>
                    <span class="px-4 py-2 bg-surface-container text-on-surface-variant rounded-full text-sm font-medium hover:bg-surface-container-high cursor-pointer">Edukasi</span>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 auto-rows-[200px]">
                <div class="col-span-2 row-span-2 rounded-2xl overflow-hidden group relative">
                    <img alt="Gallery 1" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB_aGAWZ9e1r4xmn_6_Quv7oxruCW9Wc16_RBCedlyBxIBie5VHJBNJSx-ElNT35TxW4V9fqOlFaqGjnTl1Yt5qeY_P6xCEPLgtL8E0ePbzgnh4NS1bQtURGSOAApZqCcZhrGQFWfuBXkVfNLbcCi6h_yr-hTXa6Nw12ITy55Be20lbWPo2Vlxo_uUbeo8y7VQqEn5l_pWlF9DSKTT4iW9pL0Q816jP5Oz-WCaiFg6fuEHmUX7fZTTKdjC7HGF1zKS1c00YaBv7_fo"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                        <p class="text-white font-medium">Distribusi Bantuan Pangan Nasional 2024</p>
                    </div>
                </div>
                <div class="col-span-2 row-span-1 rounded-2xl overflow-hidden group relative">
                    <img alt="Gallery 2" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuD7m2CDjZYN7umd9A_-j1uAHY6pBGT569VBqUan3e6D3dnxA8V8kzW-1VCMTsSas54q4tkjlDsneaj6Fqa1mqM69N0Kx2l1zoyHYftzY_s3_bhuW9Y_JhjUNI2OYubxNGTW3WKUJIv1hvsmcso5AwkWXJdKV4nP7GtV8sabCVLPQ282Uei5MdfdeyeWMP9lRRt-WALmjN7jXTflVPo4_GpTiJLfxOilJh2B8NR6v6D05c02SqXLJXBwP7cqoO7ON74fG7HdRFGXMVo"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-6">
                        <p class="text-white font-medium">Layanan Home Care Lansia</p>
                    </div>
                </div>
                <div class="col-span-1 row-span-1 rounded-2xl overflow-hidden group relative">
                    <img alt="Gallery 3" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA9uPANjqFrd1XtTIgdsZRO7xzEoOSycrwz-JaV_MWpoZzBEcnfTr4Xx8eyixYh4Ar2zNmARPGyMAotsmbcji7omh8w1xYxFLOwwoLSSKwK_jWvXAwjoHAysudbfEmqQYp9KIwRGMWVYZ-hDQlLbD3KCMD7sz0mA5CzrMo1XxLo8f6wB01QGD9wXtDSgXjVwrIrsO3cT1C39vUCQeO5bi7Yv1RB0YbEOB-VCWI2teOrBoGcLhWlSoiNCX2m1pZ8CDDJ22aL6gw8Z2g"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                        <p class="text-white text-xs font-medium">Program Literasi Desa</p>
                    </div>
                </div>
                <div class="col-span-1 row-span-1 rounded-2xl overflow-hidden group relative">
                    <img alt="Gallery 4" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAqQCF0IFlyA9jxtydBVJLyDIvN5b7-Nu-I3IhSn-dWI-Vgx9muDn6hGZSavBQjB0yDk-mLU32wsKuitTL6AuVkNl-HmKAMRGFZxetut9tvvquuza1GibwiOMNhvSEEgF-yGN67DCEpMLM1PNBf3zJB6vaXWMAuIiZ2-3PItdfEpeFqb70oMDaf_6rZE7Eq_aYeSizNy-EQX2cCG6SoVMd1SM44GqqZUmf0xDKcEPdx76LsdIFnbLg91uPkT7QRUYjk6IvX2EBmt-I"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-primary/80 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                        <p class="text-white text-xs font-medium">Pelatihan Digital KPM</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="py-24 bg-primary overflow-hidden">
        <div class="max-w-7xl mx-auto px-8 grid md:grid-cols-2 gap-20 items-center">
            <div class="relative">
                <div class="absolute -top-12 -left-12 w-64 h-64 bg-primary-container/20 rounded-full blur-3xl"></div>
                <div class="relative z-10 bg-white/5 backdrop-blur-xl border border-white/10 p-10 rounded-3xl shadow-2xl">
                    <h2 class="text-3xl font-bold text-white mb-6">Misi Kami</h2>
                    <p class="text-on-primary-container text-lg leading-relaxed mb-8">
                        "Menjamin tidak ada satu pun warga negara yang tertinggal dalam jaring pengaman sosial melalui inovasi teknologi dan ketepatan data."
                    </p>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-on-primary-container">
                                <span class="material-symbols-outlined" data-icon="verified">verified</span>
                            </div>
                            <div>
                                <h5 class="text-white font-bold">Data Terintegrasi</h5>
                                <p class="text-slate-400 text-sm">Sinkronisasi data real-time dengan Dukcapil dan Kemendagri.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-on-primary-container">
                                <span class="material-symbols-outlined" data-icon="visibility">visibility</span>
                            </div>
                            <div>
                                <h5 class="text-white font-bold">Transparansi Publik</h5>
                                <p class="text-slate-400 text-sm">Laporan penyaluran yang dapat dipantau oleh siapa pun.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-y-8">
                <h2 class="text-4xl font-bold text-white tracking-tight">Menjangkau yang Tak Terjangkau, Memberi yang Membutuhkan.</h2>
                <p class="text-on-primary-container text-xl font-light">
                    Bantu-In bukan sekadar aplikasi, melainkan jembatan kebaikan antara pemerintah dan rakyat untuk menciptakan Indonesia yang lebih adil dan sejahtera.
                </p>
                <div class="flex flex-wrap gap-8 md:gap-12 border-t border-white/10 pt-12 justify-center md:justify-start">
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-white">45jt+</div>
                        <div class="text-on-primary-container text-sm uppercase tracking-widest mt-2">Penerima Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-white">99%</div>
                        <div class="text-on-primary-container text-sm uppercase tracking-widest mt-2">Tingkat Akurasi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-extrabold text-white">514</div>
                        <div class="text-on-primary-container text-sm uppercase tracking-widest mt-2">Kota/Kabupaten</div>
                    </div>
                </div>
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
    </script>
</body>
</html>
