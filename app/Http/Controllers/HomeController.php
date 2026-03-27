<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use App\Models\Pengajuan;

class HomeController extends Controller
{
    public function index()
    {

        if (URL::previous() === route('login'))
            toast()->success('Success !!', 'Berhasil masuk ke sistem.');

        $totalPerorangan = random_int(100, 1000);
        $totalOrganisasi = random_int(100, 1000);
        $totalPengajuan = Pengajuan::where('user_id', auth()->id())->count();
        $totalVerifikasi = Pengajuan::where('user_id', auth()->id())->whereNotNull('verified_at')->count();
        $totalBelumVerifikasi = Pengajuan::where('user_id', auth()->id())->whereNull('verified_at')->count();
        $totalRealisasi = 0;
        $totalBansos = random_int(1000000, 100000000);

        $dataLabel = array_map(fn($month) => Carbon::create(null, $month)->format('F'), range(1, 12));

        $dataChartPengajuan = ["labels" => [], "data" => []];
        $dataChartBansos = ["labels" => [], "data" => []];
        foreach ($dataLabel as $bulan) {
            $dataChartPengajuan["labels"][] = $bulan;
            $dataChartBansos["labels"][] = $bulan;
            foreach (range(1, count($dataLabel)) as $index) {
                $dataChartPengajuan["data"]["Jumlah"][] = random_int(50, 200);
                $dataChartBansos["data"]["Rupiah"][] = random_int(2000000, 10000000);
            }
        }

        if (auth()->user()->is_user()) {
            auth()->user()->load('userDetail.desa');
            return view('home-user', compact(
                'totalPerorangan',
                'totalOrganisasi',
                'totalVerifikasi',
                'totalBelumVerifikasi',
                'totalRealisasi',
                'totalBansos',
                'totalPengajuan',
                'dataLabel',
                'dataChartPengajuan',
                'dataChartBansos'
            ));
        }

        if (auth()->user()->is_opd()) {
            return view('home-opd', compact(
                'totalPerorangan',
                'totalOrganisasi',
                'totalBansos',
                'totalPengajuan'
            ));
        }

        return view('home', compact(
            'totalPerorangan',
            'totalOrganisasi',
            'totalBansos',
            'totalPengajuan',
            'dataLabel',
            'dataChartPengajuan',
            'dataChartBansos'
        ));
    }
}
