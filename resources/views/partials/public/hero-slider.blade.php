{{-- Hero carousel: gambar + overlay + teks kiri; tanpa tombol; auto-slide di landing.blade.php --}}
<section id="hero-slider-root" class="relative h-[870px] overflow-hidden" data-autoplay-ms="6500" aria-label="Sorotan beranda">
    @if($heroSlides->isEmpty())
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-primary"></div>
            <div class="absolute inset-0 hero-gradient"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-8 h-full flex items-center">
            <div class="max-w-2xl space-y-6 text-left">
                <span class="inline-block px-4 py-1.5 bg-primary-container/30 text-white rounded-full text-sm font-medium backdrop-blur-sm">Program Pemerintah Resmi</span>
                <h1 class="text-5xl md:text-7xl font-bold text-white leading-[1.1] tracking-tight">
                    Gotong Royong Membangun <span class="text-on-primary-container">Kesejahteraan.</span>
                </h1>
                <p class="text-xl text-slate-200 font-light leading-relaxed">
                    Akses layanan bantuan sosial terpadu untuk masyarakat Indonesia. Transparan, akuntabel, dan tepat sasaran bagi yang membutuhkan.
                </p>
            </div>
        </div>
    @else
        @foreach($heroSlides as $slide)
            @php
                $heroUrl = $slide->getFirstMediaUrl('hero');
            @endphp
            <div
                data-hero-slide
                class="absolute inset-0 transition-opacity duration-700 ease-in-out {{ $loop->first ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                @if($loop->first) aria-current="true" @endif
            >
                <div class="absolute inset-0">
                    @if($heroUrl !== '')
                        <img src="{{ $heroUrl }}" alt="{{ $slide->judul }}" class="w-full h-full object-cover"/>
                    @else
                        <div class="w-full h-full bg-primary"></div>
                    @endif
                    <div class="absolute inset-0 hero-gradient"></div>
                </div>
                <div class="relative max-w-7xl mx-auto px-8 h-full flex items-center">
                    <div class="max-w-2xl space-y-6 text-left">
                        <span class="inline-block px-4 py-1.5 bg-primary-container/30 text-white rounded-full text-sm font-medium backdrop-blur-sm">{{ $slide->kategori }}</span>
                        <h1 class="text-5xl md:text-7xl font-bold text-white leading-[1.1] tracking-tight">
                            {{ $slide->judul }}@if(filled($slide->judul_sorot)) <span class="text-on-primary-container">{{ $slide->judul_sorot }}</span>@endif
                        </h1>
                        <p class="text-xl text-slate-200 font-light leading-relaxed">
                            {{ $slide->subtitle }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</section>
