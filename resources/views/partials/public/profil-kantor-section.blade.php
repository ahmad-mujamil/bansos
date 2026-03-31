@php
    /** @var \App\Models\ProfilKantor|null $profilKantor */
    $p = $profilKantor;
    $fotoKadis = $p
        ? ($p->getFirstMediaUrl('foto_kepala_dinas', 'thumb') ?:
        $p->getFirstMediaUrl('foto_kepala_dinas'))
        : '';
    $fotoSekdis = $p ? ($p->getFirstMediaUrl('foto_sekdis', 'thumb') ?: $p->getFirstMediaUrl('foto_sekdis')) : '';
    $judulInstansi = filled($p?->nama_instansi) ? $p->nama_instansi : 'Profil Kantor';
    $adaIsi =
        $p &&
        (filled($p->nama_instansi) ||
            filled($p->kepala_dinas) ||
            filled($p->sekdis) ||
            filled($p->lokasi_kantor) ||
            filled($p->no_telepon) ||
            filled($p->email) ||
            filled($p->website) ||
            $fotoKadis !== '' ||
            $fotoSekdis !== '');
@endphp

<section
    class="relative scroll-mt-24 py-24 overflow-hidden bg-gradient-to-b from-surface-container-low via-surface to-surface-container-low"
    id="profil-kantor" aria-labelledby="profil-kantor-heading">
    <div class="pointer-events-none absolute -top-24 right-0 h-80 w-80 rounded-full bg-primary/5 blur-3xl"></div>
    <div class="pointer-events-none absolute bottom-0 left-0 h-64 w-64 rounded-full bg-primary-container/10 blur-3xl">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-8">
        <div class="mx-auto max-w-2xl text-center mb-14 md:mb-16">
            <p class="text-xs font-bold tracking-[0.2em] text-primary-container uppercase">Instansi pemerintah</p>
            <h2 id="profil-kantor-heading" class="mt-3 text-4xl font-bold text-primary tracking-tight">
                {{ $judulInstansi }}
            </h2>
            <p class="mt-4 text-on-surface-variant text-lg leading-relaxed">
                @if ($adaIsi)
                    Informasi dan kontak layanan untuk mendukung transparansi dan kemudahan masyarakat.
                @endif
            </p>
        </div>

        @if ($adaIsi)
            <div class="grid gap-8 md:grid-cols-2 mb-14">
                {{-- Kepala dinas --}}
                <article
                    class="group relative rounded-3xl border border-outline-variant bg-surface-container-lowest p-8 shadow-sm transition-all duration-300 hover:border-primary/30 hover:shadow-lg md:p-10">
                    <div
                        class="absolute inset-x-8 top-0 h-1 rounded-b-full bg-gradient-to-r from-transparent via-primary to-transparent opacity-60">
                    </div>
                    <div class="flex flex-col items-center gap-8 sm:flex-row sm:items-stretch">
                        <div class="relative shrink-0 mx-auto sm:mx-0">
                            <div
                                class="absolute -inset-1 rounded-2xl bg-gradient-to-br from-primary/20 to-primary-container/30 opacity-80 blur-sm transition group-hover:opacity-100">
                            </div>
                            <div
                                class="relative h-52 w-40 overflow-hidden rounded-2xl bg-surface-container ring-4 ring-white shadow-xl">
                                @if ($fotoKadis !== '')
                                    <img src="{{ $fotoKadis }}" alt="Foto Kepala Dinas"
                                        class="h-full w-full object-cover object-top" loading="lazy" decoding="async" />
                                @else
                                    <div
                                        class="flex h-full w-full flex-col items-center justify-center gap-2 bg-surface-container-high text-on-surface-variant">
                                        <span class="material-symbols-outlined text-5xl opacity-40"
                                            data-icon="person">person</span>
                                        <span class="text-xs font-medium opacity-70">Foto</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="min-w-0 flex-1 text-center sm:text-left flex flex-col justify-center">
                            <span
                                class="inline-flex items-center justify-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-primary sm:inline-flex">
                                <span class="material-symbols-outlined text-sm" data-icon="badge">badge</span>
                                Kepala Dinas
                            </span>
                            <h3 class="mt-4 text-2xl font-bold text-primary leading-snug">
                                {{ filled($p->kepala_dinas) ? $p->kepala_dinas : '—' }}
                            </h3>
                            @if (filled($p->nip_kepala_dinas))
                                <p class="mt-2 text-sm text-on-surface-variant">
                                    <span class="font-semibold text-on-surface">NIP</span>
                                    {{ $p->nip_kepala_dinas }}
                                </p>
                            @endif
                        </div>
                    </div>
                </article>

                {{-- Sekdis --}}
                <article
                    class="group relative rounded-3xl border border-outline-variant bg-surface-container-lowest p-8 shadow-sm transition-all duration-300 hover:border-primary/30 hover:shadow-lg md:p-10">
                    <div
                        class="absolute inset-x-8 top-0 h-1 rounded-b-full bg-gradient-to-r from-transparent via-secondary to-transparent opacity-50">
                    </div>
                    <div class="flex flex-col items-center gap-8 sm:flex-row sm:items-stretch">
                        <div class="relative shrink-0 mx-auto sm:mx-0">
                            <div
                                class="absolute -inset-1 rounded-2xl bg-gradient-to-br from-secondary/15 to-tertiary-fixed/40 opacity-80 blur-sm transition group-hover:opacity-100">
                            </div>
                            <div
                                class="relative h-52 w-40 overflow-hidden rounded-2xl bg-surface-container ring-4 ring-white shadow-xl">
                                @if ($fotoSekdis !== '')
                                    <img src="{{ $fotoSekdis }}" alt="Foto Sekretaris Dinas"
                                        class="h-full w-full object-cover object-top" loading="lazy" decoding="async" />
                                @else
                                    <div
                                        class="flex h-full w-full flex-col items-center justify-center gap-2 bg-surface-container-high text-on-surface-variant">
                                        <span class="material-symbols-outlined text-5xl opacity-40"
                                            data-icon="person">person</span>
                                        <span class="text-xs font-medium opacity-70">Foto</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="min-w-0 flex-1 text-center sm:text-left flex flex-col justify-center">
                            <span
                                class="inline-flex items-center justify-center gap-1.5 rounded-full bg-secondary/15 px-3 py-1 text-xs font-bold uppercase tracking-wide text-secondary sm:inline-flex">
                                <span class="material-symbols-outlined text-sm"
                                    data-icon="description">description</span>
                                Sekretaris Dinas
                            </span>
                            <h3 class="mt-4 text-2xl font-bold text-primary leading-snug">
                                {{ filled($p->sekdis) ? $p->sekdis : '—' }}
                            </h3>
                            @if (filled($p->nip_sekdis))
                                <p class="mt-2 text-sm text-on-surface-variant">
                                    <span class="font-semibold text-on-surface">NIP</span>
                                    {{ $p->nip_sekdis }}
                                </p>
                            @endif
                        </div>
                    </div>
                </article>
            </div>

            @if (filled($p->lokasi_kantor) || filled($p->no_telepon) || filled($p->email) || filled($p->website))
                <div class="rounded-3xl border border-outline-variant bg-primary/[0.03] p-8 md:p-10 backdrop-blur-sm">
                    <h3 class="text-center text-sm font-bold uppercase tracking-widest text-primary mb-8">
                        Kontak & lokasi
                    </h3>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @if (filled($p->lokasi_kantor))
                            <div
                                class="flex gap-4 rounded-2xl bg-surface-container-lowest p-5 border border-outline-variant/60">
                                <span
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary text-white shadow-md">
                                    <span class="material-symbols-outlined" data-icon="location_on">location_on</span>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold uppercase tracking-wide text-on-surface-variant">
                                        Alamat
                                    </div>
                                    <p class="mt-1 text-sm font-medium text-on-surface leading-relaxed">
                                        {{ $p->lokasi_kantor }}</p>
                                </div>
                            </div>
                        @endif
                        @if (filled($p->no_telepon))
                            <a href="tel:{{ preg_replace('/\s+/', '', $p->no_telepon) }}"
                                class="flex gap-4 rounded-2xl bg-surface-container-lowest p-5 border border-outline-variant/60 transition hover:border-primary/40 hover:shadow-md">
                                <span
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-primary-container text-white shadow-md">
                                    <span class="material-symbols-outlined" data-icon="call">call</span>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold uppercase tracking-wide text-on-surface-variant">
                                        Telepon</div>
                                    <p class="mt-1 text-sm font-semibold text-primary">{{ $p->no_telepon }}</p>
                                </div>
                            </a>
                        @endif
                        @if (filled($p->email))
                            <a href="mailto:{{ $p->email }}"
                                class="flex gap-4 rounded-2xl bg-surface-container-lowest p-5 border border-outline-variant/60 transition hover:border-primary/40 hover:shadow-md">
                                <span
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-tertiary text-white shadow-md">
                                    <span class="material-symbols-outlined" data-icon="mail">mail</span>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold uppercase tracking-wide text-on-surface-variant">Email
                                    </div>
                                    <p class="mt-1 text-sm font-semibold text-primary break-all">{{ $p->email }}
                                    </p>
                                </div>
                            </a>
                        @endif
                        @if (filled($p->website))
                            <a href="{{ $p->website }}" target="_blank" rel="noopener noreferrer"
                                class="flex gap-4 rounded-2xl bg-surface-container-lowest p-5 border border-outline-variant/60 transition hover:border-primary/40 hover:shadow-md">
                                <span
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-secondary text-white shadow-md">
                                    <span class="material-symbols-outlined" data-icon="language">language</span>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-bold uppercase tracking-wide text-on-surface-variant">
                                        Website
                                    </div>
                                    <p class="mt-1 text-sm font-semibold text-primary line-clamp-2 break-all">
                                        {{ Str::of($p->website)->replace(['https://', 'http://'], '') }}</p>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        @else
            <div
                class="mx-auto max-w-xl rounded-3xl border border-dashed border-outline-variant bg-surface-container-lowest/80 px-8 py-14 text-center">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-4 block mx-auto"
                    data-icon="apartment">apartment</span>
                <p class="text-on-surface-variant leading-relaxed">
                    Data profil kantor belum tersedia.
                </p>
            </div>
        @endif
    </div>
</section>
