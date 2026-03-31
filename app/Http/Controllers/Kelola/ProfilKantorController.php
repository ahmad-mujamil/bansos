<?php

namespace App\Http\Controllers\Kelola;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfilKantorRequest;
use App\Models\ProfilKantor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ProfilKantorController extends Controller
{
    public function edit(): View
    {
        $profilKantor = ProfilKantor::instance();

        return view('pages.kelola.profil-kantor.form', compact('profilKantor'));
    }

    public function update(ProfilKantorRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $profilKantor = ProfilKantor::instance();
            $profilKantor->update(
                collect($request->validated())->except(['foto_kepala_dinas', 'foto_sekdis'])->all()
            );

            if ($request->hasFile('foto_kepala_dinas')) {
                $profilKantor->clearMediaCollection('foto_kepala_dinas');
                $profilKantor->addMediaFromRequest('foto_kepala_dinas')->toMediaCollection('foto_kepala_dinas');
            }

            if ($request->hasFile('foto_sekdis')) {
                $profilKantor->clearMediaCollection('foto_sekdis');
                $profilKantor->addMediaFromRequest('foto_sekdis')->toMediaCollection('foto_sekdis');
            }

            DB::commit();
            toast()->success('Yeeayy !!', 'Profil kantor berhasil disimpan');

            return redirect()->route('profil-kantor.edit');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }
}
