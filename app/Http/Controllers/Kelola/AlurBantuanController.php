<?php

namespace App\Http\Controllers\Kelola;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class AlurBantuanController extends Controller
{
    public function edit(): View
    {
        return view('pages.kelola.alur-bantuan.form');
    }
}
