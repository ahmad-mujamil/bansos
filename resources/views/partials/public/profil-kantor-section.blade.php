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

<section class="scroll-mt-24 py-20 md:py-28 bg-surface"
    id="profil-kantor" aria-labelledby="profil-kantor-heading">
    <div class="max-w-7xl mx-auto px-6 md:px-8">
        <div class="max-w-2xl mb-12 pb-6 border-b border-outline-variant">
            <h2 id="profil-kantor-heading" class="heading text-3xl md:text-4xl text-on-surface">
                {{ $judulInstansi }}
            </h2>
            @if ($adaIsi)
                <p class="mt-3 text-on-surface-variant leading-relaxed prose-narrow">
                    Informasi dan kontak layanan Si-BATUR.
                </p>
            @endif
        </div>

        @if ($adaIsi)
            <div class="grid gap-8 md:grid-cols-2 mb-14">
                {{-- Kepala dinas --}}
                <article
                    class="group relative rounded-lg border border-outline-variant bg-surface-container-lowest p-8 transition-colors hover:border-primary/40 md:p-10">
                    <div class="flex flex-col items-center gap-8 sm:flex-row sm:items-stretch">
                        <div class="relative shrink-0 mx-auto sm:mx-0">
                            <div
                                class="absolute -inset-px rounded-lg bg-primary/15">
                            </div>
                            <div
                                class="relative h-52 w-40 overflow-hidden rounded-lg bg-surface-container">
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
                                class="inline-flex items-center justify-center gap-1.5 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold text-primary sm:inline-flex">
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
                    class="group relative rounded-lg border border-outline-variant bg-surface-container-lowest p-8 transition-colors hover:border-primary/40 md:p-10">
                    <div
                        class="absolute inset-x-8 top-0 h-1 rounded-b-full bg-gradient-to-r from-transparent via-secondary to-transparent opacity-50">
                    </div>
                    <div class="flex flex-col items-center gap-8 sm:flex-row sm:items-stretch">
                        <div class="relative shrink-0 mx-auto sm:mx-0">
                            <div
                                class="absolute -inset-px rounded-lg bg-primary/15">
                            </div>
                            <div
                                class="relative h-52 w-40 overflow-hidden rounded-lg bg-surface-container">
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
                                class="inline-flex items-center justify-center gap-1.5 rounded-full bg-secondary/15 px-3 py-1 text-xs font-semibold text-secondary sm:inline-flex">
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
                <div class="rounded-lg border border-outline-variant bg-surface-container-low p-8 md:p-10">
                    <h3 class="text-xl font-bold text-on-surface mb-7">Kontak &amp; lokasi</h3>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        @if (filled($p->lokasi_kantor))
                            <div
                                class="flex gap-4 rounded-md bg-surface-container-lowest p-5 border border-outline-variant">
                                <span
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-primary text-white">
                                    <span class="material-symbols-outlined" data-icon="location_on">location_on</span>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold text-on-surface-variant">
                                        Alamat
                                    </div>
                                    <p class="mt-1 text-sm font-medium text-on-surface leading-relaxed">
                                        {{ $p->lokasi_kantor }}</p>
                                </div>
                            </div>
                        @endif
                        @if (filled($p->no_telepon))
                            <a href="tel:{{ preg_replace('/\s+/', '', $p->no_telepon) }}"
                                class="flex gap-4 rounded-md bg-surface-container-lowest p-5 border border-outline-variant transition-colors hover:border-primary">
                                <span
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-primary text-white">
                                    <span class="material-symbols-outlined" data-icon="call">call</span>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold text-on-surface-variant">
                                        Telepon</div>
                                    <p class="mt-1 text-sm font-semibold text-primary">{{ $p->no_telepon }}</p>
                                </div>
                            </a>
                        @endif
                        @if (filled($p->email))
                            <a href="mailto:{{ $p->email }}"
                                class="flex gap-4 rounded-md bg-surface-container-lowest p-5 border border-outline-variant transition-colors hover:border-primary">
                                <span
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-primary text-white">
                                    <span class="material-symbols-outlined" data-icon="mail">mail</span>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold text-on-surface-variant">Email
                                    </div>
                                    <p class="mt-1 text-sm font-semibold text-primary break-all">{{ $p->email }}
                                    </p>
                                </div>
                            </a>
                        @endif
                        @if (filled($p->website))
                            <a href="{{ $p->website }}" target="_blank" rel="noopener noreferrer"
                                class="flex gap-4 rounded-md bg-surface-container-lowest p-5 border border-outline-variant transition-colors hover:border-primary">
                                <span
                                    class="flex h-11 w-11 shrink-0 items-center justify-center rounded-md bg-primary text-white">
                                    <span class="material-symbols-outlined" data-icon="language">language</span>
                                </span>
                                <div class="min-w-0">
                                    <div class="text-xs font-semibold text-on-surface-variant">
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
                class="mx-auto max-w-xl rounded-lg border border-dashed border-outline-variant px-8 py-14 text-center">
                <span class="material-symbols-outlined text-5xl text-on-surface-variant/40 mb-4 block mx-auto"
                    data-icon="apartment">apartment</span>
                <p class="text-on-surface-variant leading-relaxed">
                    Data profil kantor belum tersedia.
                </p>
            </div>
        @endif
    </div>
</section>
