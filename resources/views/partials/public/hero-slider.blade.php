{{-- Hero: ilustrasi penuh + veil merah + teks kiri; auto-slide diatur di landing.blade.php --}}
@php
    $heroFallback = asset('img/background/background-2.jpeg');
    $heroFallbackAlt = 'Petani, nelayan, dan peternak Lombok Barat menerima sertifikat dan paket bantuan';
@endphp
<section id="hero-slider-root"
         class="relative min-h-[32rem] h-[78vh] max-h-[46rem] overflow-hidden bg-primary-container"
         data-autoplay-ms="6500"
         aria-label="Sorotan beranda">
    @if($heroSlides->isEmpty())
        <div class="absolute inset-0">
            <img src="{{ $heroFallback }}" alt="{{ $heroFallbackAlt }}" class="w-full h-full object-cover"/>
            <div class="absolute inset-0 hero-veil" aria-hidden="true"></div>
        </div>
        <div class="relative h-full max-w-7xl mx-auto px-6 md:px-8 flex items-center">
            <div class="max-w-xl space-y-6 hero-rise">
                <h1 class="display text-white text-4xl sm:text-5xl lg:text-6xl">
                    Bantuan untuk warga Lombok Barat, satu pintu.
                </h1>
                <p class="text-lg leading-relaxed text-white/85">
                    Ajukan bantuan sosial, hibah, atau bantuan kelompok masyarakat &mdash;
                    lalu pantau prosesnya sampai selesai.
                </p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="{{ route('login') }}"
                       class="inline-flex items-center rounded-md bg-white px-6 py-3 font-bold text-primary transition-colors hover:bg-on-primary-container">
                        Masuk
                    </a>
                    <a href="{{ url('/#assistance') }}"
                       class="inline-flex items-center rounded-md border border-white/40 px-6 py-3 font-semibold text-white transition-colors hover:bg-white/10">
                        Lihat alur bantuan
                    </a>
                </div>
            </div>
        </div>
    @else
        @foreach($heroSlides as $slide)
            @php $heroUrl = $slide->getFirstMediaUrl('hero'); @endphp
            <div
                data-hero-slide
                class="absolute inset-0 transition-opacity duration-700 ease-in-out {{ $loop->first ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                @if($loop->first) aria-current="true" @endif
            >
                <div class="absolute inset-0">
                    <img src="{{ $heroUrl !== '' ? $heroUrl : $heroFallback }}"
                         alt="{{ $heroUrl !== '' ? $slide->judul : $heroFallbackAlt }}"
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null;this.src='{{ $heroFallback }}';"/>
                    <div class="absolute inset-0 hero-veil" aria-hidden="true"></div>
                </div>
                <div class="relative h-full max-w-7xl mx-auto px-6 md:px-8 flex items-center">
                    <div class="max-w-xl space-y-5 {{ $loop->first ? 'hero-rise' : '' }}">
                        @if(filled($slide->kategori))
                            <p class="text-sm font-semibold text-on-primary-container">{{ $slide->kategori }}</p>
                        @endif
                        <h1 class="display text-white text-4xl sm:text-5xl lg:text-6xl">
                            {{ $slide->judul }}@if(filled($slide->judul_sorot) && ! str_contains($slide->judul, $slide->judul_sorot)) {{ $slide->judul_sorot }}@endif
                        </h1>
                        <p class="text-lg leading-relaxed text-white/85">
                            {{ $slide->subtitle }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</section>
