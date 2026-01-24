<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class HomeController extends Controller
{
    public function index()
    {

        if (URL::previous() === route('login'))
            toast()->success('Success !!', 'Berhasil masuk ke sistem.');

        $totalPerorangan = random_int(100, 1000);
        $totalOrganisasi = random_int(100, 1000);
        $totalPengajuan = random_int(100, 1000);
        $totalBansos = random_int(1000000, 100000000);

        $dataLabel = array_map(fn($month) => Carbon::create(null, $month)->format('F'), range(1, 12));

        $dataChartPengajuan = ["labels" => [], "data" => []];
        $dataChartBansos = ["labels" => [], "data" => []];
        foreach ($dataLabel as $bulan) {
            $dataChartPengajuan["labels"][] = $bulan;
            $dataChartBansos["labels"][] = $bulan;
            foreach (range(1,count($dataLabel)) as $index) {
                $dataChartPengajuan["data"]["Jumlah"][] = random_int(50, 200);
                $dataChartBansos["data"]["Rupiah"][] = random_int(2000000, 10000000);
            }
        }

        return view('home', compact('totalPerorangan',
            'totalOrganisasi',
            'totalBansos',
            'totalPengajuan',
            'dataLabel',
            'dataChartPengajuan',
            'dataChartBansos'));
    }

}
