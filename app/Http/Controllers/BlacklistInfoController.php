<?php

namespace App\Http\Controllers;

use App\Models\LogBlacklistOrganisasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BlacklistInfoController extends Controller
{
    public function __invoke(): View|RedirectResponse
    {
        $user = Auth::user();

        $organisasi = $user?->userDetail?->organisasi;

        if ($organisasi === null) {
            toast()->warning('Data tidak ditemukan', 'Organisasi/kelompok Anda tidak ditemukan.');
            return redirect()->route('home');
        }

        if (! (bool) ($organisasi->is_blacklist ?? false)) {
            toast()->info('Info', 'Organisasi/kelompok Anda tidak sedang diblacklist.');
            return redirect()->route('home');
        }

        $lastBlacklistLog = LogBlacklistOrganisasi::query()
            ->where('organisasi_id', $organisasi->id)
            ->where('jadi_blacklist', true)
            ->latest()
            ->with(['user'])
            ->first();

        return view('pages.blacklist.info', [
            'organisasi' => $organisasi,
            'lastBlacklistLog' => $lastBlacklistLog,
        ]);
    }
}

