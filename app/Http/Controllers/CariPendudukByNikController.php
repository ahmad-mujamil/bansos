<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class CariPendudukByNikController extends Controller
{
    public function __invoke(): View
    {
        return view('pages.cari-penduduk-by-nik');
    }
}
