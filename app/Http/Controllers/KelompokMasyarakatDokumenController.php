<?php

namespace App\Http\Controllers;

use App\Enums\JenisOrganisasi;
use App\Http\Requests\KelompokMasyarakatDokumenRequest;
use App\Models\Organisasi;
use App\Models\OrganisasiDokumen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class KelompokMasyarakatDokumenController extends Controller
{
    public function index(string $kelompok_masyarakat)
    {
        $organisasi = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->findOrFail($kelompok_masyarakat);

        confirmDelete("Hapus Dokumen", "Apakah Anda yakin ingin menghapus dokumen ini?");
        if (request()->ajax()) {
            return $this->data($kelompok_masyarakat);
        }
        return view('pages.kelompok-masyarakat.dokumen.index', compact('organisasi'));
    }

    private function data(string $organisasiId): \Illuminate\Http\JsonResponse
    {
        $data = OrganisasiDokumen::query()
            ->where('organisasi_id', $organisasiId)
            ->latest();

        return DataTables::of($data)
            ->addColumn('jenis_label', fn($row) => $row->jenis_dokumen?->getDescription() ?? $row->jenis_dokumen)
            ->addColumn('file_info', function ($row) {
                $media = $row->getFirstMedia('dokumen');
                if (!$media) {
                    return '<span class="text-muted">-</span>';
                }
                $url = $media->getUrl();
                $mime = $media->mime_type ?? '';
                return '<a href="' . e($url) . '" class="btn-preview-dokumen fw-bold text-primary" data-url="' . e($url) . '" data-mime="' . e($mime) . '" data-filename="' . e($media->file_name) . '" title="Preview">' . e($media->file_name) . '</a>';
            })
            ->addColumn('action', function ($row) use ($organisasiId) {
                $media = $row->getFirstMedia('dokumen');
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = "</ul></nav>";

                $preview = $media
                    ? "<li class='breadcrumb-item'><a href='#' class='btn-preview-dokumen fw-bold text-primary' data-url='" . e($media->getUrl()) . "' data-mime='" . e($media->mime_type ?? '') . "' data-filename='" . e($media->file_name) . "' title='Preview'>Preview</a></li>"
                    : '';

                $edit = "<li class='breadcrumb-item'><a href='#' class='btn-edit-dokumen fw-bold text-success' data-dokumen-id='" . e($row->id) . "' data-jenis-dokumen='" . e($row->jenis_dokumen?->value ?? '') . "' data-keterangan='" . e($row->keterangan) . "' title='Edit'>Edit</a></li>";

                $delete = "<li class='breadcrumb-item'><a href='" . route('kelompok-masyarakat.dokumen.destroy', [$organisasiId, $row->id]) . "' data-confirm-delete='true' title='Hapus' class='fw-bold text-danger'>Delete</a></li>";

                return $navActionStart . $preview . $edit . $delete . $navActionEnd;
            })
            ->rawColumns(['file_info', 'action'])
            ->toJson();
    }

    public function create(string $kelompok_masyarakat)
    {
        $organisasi = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->findOrFail($kelompok_masyarakat);

        return view('pages.kelompok-masyarakat.dokumen.create', compact('organisasi'));
    }

    public function store(KelompokMasyarakatDokumenRequest $request, string $kelompok_masyarakat)
    {
        $organisasi = Organisasi::query()
            ->where('jenis', JenisOrganisasi::KELOMPOK)
            ->findOrFail($kelompok_masyarakat);

        try {
            DB::beginTransaction();
            $dokumen = OrganisasiDokumen::query()->create([
                'organisasi_id' => $organisasi->id,
                'keterangan' => $request->validated('keterangan'),
                'jenis_dokumen' => $request->validated('jenis_dokumen'),
            ]);

            if ($request->hasFile('file')) {
                $dokumen->addMediaFromRequest('file')->toMediaCollection('dokumen');
            }
            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Dokumen berhasil diunggah']);
            }
            toast()->success('Yeeayy !!', 'Dokumen berhasil diunggah');
            return redirect()->route('kelompok-masyarakat.dokumen.index', $kelompok_masyarakat);
        } catch (\Throwable $th) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
            }
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function update(KelompokMasyarakatDokumenRequest $request, string $kelompok_masyarakat, string $dokumen)
    {
        $row = OrganisasiDokumen::query()
            ->where('organisasi_id', $kelompok_masyarakat)
            ->findOrFail($dokumen);

        try {
            DB::beginTransaction();
            $row->update([
                'keterangan' => $request->validated('keterangan'),
                'jenis_dokumen' => $request->validated('jenis_dokumen'),
            ]);

            if ($request->hasFile('file')) {
                $row->clearMediaCollection('dokumen');
                $row->addMediaFromRequest('file')->toMediaCollection('dokumen');
            }
            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Dokumen berhasil diperbarui']);
            }
            toast()->success('Yeeayy !!', 'Dokumen berhasil diperbarui');
            return redirect()->route('kelompok-masyarakat.dokumen.index', $kelompok_masyarakat);
        } catch (\Throwable $th) {
            DB::rollBack();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
            }
            toast()->error('Oppss !!', $th->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(string $kelompok_masyarakat, string $dokumen): \Illuminate\Http\RedirectResponse
    {
        $row = OrganisasiDokumen::query()
            ->where('organisasi_id', $kelompok_masyarakat)
            ->findOrFail($dokumen);

        try {
            DB::beginTransaction();
            $row->clearMediaCollection('dokumen');
            $row->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Dokumen berhasil dihapus');
            return redirect()->route('kelompok-masyarakat.dokumen.index', $kelompok_masyarakat);
        } catch (\Throwable $th) {
            toast()->error('Oppss !!', $th->getMessage());
            return redirect()->route('kelompok-masyarakat.dokumen.index', $kelompok_masyarakat);
        }
    }
}
