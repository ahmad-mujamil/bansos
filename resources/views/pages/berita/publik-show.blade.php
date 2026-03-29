@extends('layouts.public-bantuin')

@section('title', $berita->judul)

@section('content')
    <article>
        <nav class="text-sm text-on-surface-variant mb-6">
            <a href="{{ route('landing') }}" class="hover:text-primary">Home</a>
            <span class="mx-2">/</span>
            <a href="{{ route('berita.publik.index') }}" class="hover:text-primary">Berita</a>
            <span class="mx-2">/</span>
            <span class="text-on-surface">{{ \Illuminate\Support\Str::limit($berita->judul, 48) }}</span>
        </nav>

        <header class="mb-8">
            <span class="text-xs font-bold text-primary-container tracking-widest uppercase">{{ $berita->kategoriBerita?->nama ?? '—' }}</span>
            <h1 class="text-3xl md:text-4xl font-bold text-primary mt-2 leading-tight">{{ $berita->judul }}</h1>
            <p class="text-on-surface-variant mt-4 text-lg">{{ $berita->ringkasan }}</p>
            <div class="flex items-center gap-4 mt-4 text-sm text-slate-500">
                <span class="inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-base">calendar_today</span>
                    {{ $berita->published_at?->translatedFormat('d F Y') }}
                </span>
            </div>
        </header>

        @php $hero = $berita->getFirstMediaUrl('featured'); @endphp
        @if($hero !== '')
            <figure class="mb-10 rounded-2xl overflow-hidden">
                <img src="{{ $hero }}" alt="{{ $berita->judul }}" class="w-full max-h-[480px] object-cover"/>
            </figure>
        @endif

        <div class="max-w-none text-on-surface leading-relaxed text-base [&_p]:mb-4 [&_ul]:list-disc [&_ul]:ps-6 [&_ol]:list-decimal [&_ol]:ps-6">
            {!! $berita->konten !!}
        </div>

        <div class="mt-12 pt-8 border-t border-outline-variant">
            <a href="{{ route('berita.publik.index') }}" class="inline-flex items-center gap-2 font-semibold text-primary hover:underline">
                <span class="material-symbols-outlined">arrow_back</span> Semua berita
            </a>
        </div>
    </article>
@endsection
