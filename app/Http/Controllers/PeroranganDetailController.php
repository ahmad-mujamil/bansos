<?php

namespace App\Http\Controllers;

use App\Models\Desa;
use App\Models\Kecamatan;
use App\Models\PeroranganDetail;
use App\Enums\JenisUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PeroranganDetailController extends Controller
{
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $peroranganDetail = $user ? $user->peroranganDetail : null;

        $kecamatans = Kecamatan::with('desa')->get();
        $jenisUsaha = JenisUsaha::cases();

        return view('pages.perorangan-detail.create', compact('kecamatans', 'jenisUsaha', 'peroranganDetail'));
    }

    public function store(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            abort(403);
        }

        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:perorangan_detail,nik',
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'desa_id' => 'required|uuid|exists:desa,id',
            'pekerjaan' => 'nullable|string|max:255',
            'jenis_usaha' => 'nullable|string|max:255',
            'penghasilan' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            toast()->error('Oppss !!', 'Validasi gagal');
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $peroranganDetail = PeroranganDetail::create([
                'user_id' => $user->id,
                'nik' => $request->nik,
                'nama' => $request->nama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'desa_id' => $request->desa_id,
                'pekerjaan' => $request->pekerjaan,
                'jenis_usaha' => $request->jenis_usaha,
                'penghasilan' => $request->penghasilan,
            ]);

            // Handle dokumen upload
            $this->handleDocumentUpload($peroranganDetail, $request);
            
            // Handle validasi sosial
            $this->handleValidasiSosial($peroranganDetail, $request);

            DB::commit();
            toast()->success('Yeeayy !!', 'Data detail berhasil disimpan');
            return redirect()->route('perorangan-detail.edit');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        if (!$user || !$user->peroranganDetail) {
            return redirect()->route('perorangan-detail.create');
        }

        $kecamatans = Kecamatan::with('desa')->get();
        $jenisUsaha = JenisUsaha::cases();
        $peroranganDetail = $user->peroranganDetail;

        return view('pages.perorangan-detail.create', compact('kecamatans', 'jenisUsaha', 'peroranganDetail'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        
        if (!$user || !$user->peroranganDetail) {
            return redirect()->route('perorangan-detail.create');
        }

        $peroranganDetail = $user->peroranganDetail;

        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:perorangan_detail,nik,' . $peroranganDetail->id,
            'nama' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'desa_id' => 'required|uuid|exists:desa,id',
            'pekerjaan' => 'nullable|string|max:255',
            'jenis_usaha' => 'nullable|string|max:255',
            'penghasilan' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            toast()->error('Oppss !!', 'Validasi gagal');
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $peroranganDetail->update([
                'nik' => $request->nik,
                'nama' => $request->nama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'desa_id' => $request->desa_id,
                'pekerjaan' => $request->pekerjaan,
                'jenis_usaha' => $request->jenis_usaha,
                'penghasilan' => $request->penghasilan,
            ]);

            // Handle dokumen upload
            $this->handleDocumentUpload($peroranganDetail, $request);
            
            // Handle validasi sosial
            $this->handleValidasiSosial($peroranganDetail, $request);

            DB::commit();
            toast()->success('Yeeayy !!', 'Data detail berhasil diupdate');
            return redirect()->route('perorangan-detail.edit');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    private function handleDocumentUpload(PeroranganDetail $peroranganDetail, Request $request)
    {
        if ($request->hasFile('ktp')) {
            $peroranganDetail->clearMediaCollection(PeroranganDetail::COLLECTION_KTP);
            $peroranganDetail->addMediaFromRequest('ktp')
                ->toMediaCollection(PeroranganDetail::COLLECTION_KTP);
        }

        if ($request->hasFile('kk')) {
            $peroranganDetail->clearMediaCollection(PeroranganDetail::COLLECTION_KK);
            $peroranganDetail->addMediaFromRequest('kk')
                ->toMediaCollection(PeroranganDetail::COLLECTION_KK);
        }

        if ($request->hasFile('foto_rumah_usaha')) {
            $peroranganDetail->clearMediaCollection(PeroranganDetail::COLLECTION_FOTO_RUMAH_USAHA);
            $peroranganDetail->addMediaFromRequest('foto_rumah_usaha')
                ->toMediaCollection(PeroranganDetail::COLLECTION_FOTO_RUMAH_USAHA);
        }

        if ($request->hasFile('surat_keterangan_tidak_mampu')) {
            $peroranganDetail->clearMediaCollection(PeroranganDetail::COLLECTION_SURAT_KETERANGAN_TIDAK_MAMPU);
            $peroranganDetail->addMediaFromRequest('surat_keterangan_tidak_mampu')
                ->toMediaCollection(PeroranganDetail::COLLECTION_SURAT_KETERANGAN_TIDAK_MAMPU);
        }
    }

    private function handleValidasiSosial(PeroranganDetail $peroranganDetail, Request $request)
    {
        $peroranganDetail->update([
            'status_dtks' => $request->has('status_dtks') ? true : false,
            'penerima_bantuan_lain' => $request->penerima_bantuan_lain,
            'jumlah_tanggungan' => $request->jumlah_tanggungan ?? 0,
        ]);
    }
}

