<?php

namespace App\Http\Middleware;

use App\Models\TahunAnggaran;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetTahunAnggaran
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Schema::hasTable('tahun_anggaran')) {
            return $next($request);
        }

        $tahunList = TahunAnggaran::query()->orderByDesc('tahun')->get();

        // Default = tahun berjalan bila terdaftar; jika tidak, tahun terbaru.
        $tahunSekarang = (int) date('Y');
        $default = $tahunList->contains('tahun', $tahunSekarang)
            ? $tahunSekarang
            : ((int) $tahunList->max('tahun') ?: $tahunSekarang);

        $dipilih = (int) $request->session()->get('tahun_anggaran', $default);

        // Bila tahun di session tidak valid lagi, kembalikan ke default.
        if ($tahunList->isNotEmpty() && ! $tahunList->contains('tahun', $dipilih)) {
            $dipilih = (int) $default;
            $request->session()->put('tahun_anggaran', $dipilih);
        }

        app()->instance('tahun_anggaran_terpilih', $dipilih);

        View::share('tahunTerpilih', $dipilih);
        View::share('tahunAnggaranList', $tahunList);
        View::share('tahunTerpilihTerkunci', (bool) $tahunList->firstWhere('tahun', $dipilih)?->is_terkunci);

        return $next($request);
    }
}
