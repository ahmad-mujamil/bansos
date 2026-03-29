@extends('layouts.public-bantuin')

@section('title', 'Semua Berita')

@section('content')
    <div class="mb-10 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1 class="text-4xl font-bold text-primary">Berita</h1>
            <p class="text-on-surface-variant mt-2">Update penyaluran dan program bantuan sosial.</p>
        </div>
        <a href="{{ route('landing') }}#news" class="text-primary font-semibold inline-flex items-center gap-2 hover:underline">
            <span class="material-symbols-outlined text-lg">arrow_back</span> Kembali ke beranda
        </a>
    </div>

    @if($beritas->isEmpty())
        <div class="rounded-xl border border-outline-variant bg-surface-container-low p-12 text-center text-on-surface-variant">
            Belum ada berita terbit.
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($beritas as $berita)
                <a href="{{ route('berita.publik.show', ['berita' => $berita->slug]) }}" class="group bg-surface-container-lowest rounded-xl overflow-hidden border border-transparent hover:border-outline-variant hover:shadow-md transition-all duration-300 flex flex-col">
                    <div class="h-48 overflow-hidden bg-surface-container">
                        @php $img = $berita->getFirstMediaUrl('featured'); @endphp
                        @if($img !== '')
                            <img src="{{ $img }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"/>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-on-surface-variant text-sm">Tanpa gambar</div>
                        @endif
                    </div>
                    <div class="p-6 space-y-3 flex-1 flex flex-col">
                        <span class="text-xs font-bold text-primary-container tracking-widest uppercase">{{ $berita->kategoriBerita?->nama ?? '—' }}</span>
                        <h2 class="text-xl font-bold text-primary line-clamp-2 group-hover:text-primary-container transition-colors">{{ $berita->judul }}</h2>
                        <p class="text-on-surface-variant text-sm line-clamp-3 flex-1">{{ $berita->ringkasan }}</p>
                        <div class="flex items-center text-slate-400 text-xs pt-2">
                            <span class="material-symbols-outlined text-sm mr-1">calendar_today</span>
                            {{ $berita->published_at?->translatedFormat('d M Y') }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            {{ $beritas->withQueryString()->links('vendor.pagination.tailwind') }}
        </div>
    @endif
@endsection
