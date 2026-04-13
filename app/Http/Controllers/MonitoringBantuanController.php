<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class MonitoringBantuanController extends Controller
{
    public function index(): View
    {
        return view('pages.monitoring-bantuan.index');
    }
}
