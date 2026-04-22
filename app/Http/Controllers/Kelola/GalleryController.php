<?php

namespace App\Http\Controllers\Kelola;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    private function data(): JsonResponse
    {
        return DataTables::eloquent(Gallery::query()->ordered())
            ->addColumn('gambar', function (Gallery $row) {
                $url = $row->getFirstMediaUrl('galeri', 'thumb') ?: $row->getFirstMediaUrl('galeri');
                if ($url === '') {
                    return '<span class="text-muted">—</span>';
                }
                return '<img src="'.e($url).'" alt="" class="rounded" style="max-height:48px;max-width:80px;object-fit:cover">';
            })
            ->addColumn('aktif', fn (Gallery $row) => $row->is_active
                ? '<span class="badge bg-outline-success">Ya</span>'
                : '<span class="badge bg-outline-secondary">Tidak</span>')
            ->addColumn('action', function (Gallery $row) {
                $start = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $end   = '</ul></nav>';
                $edit  = "<li class='breadcrumb-item'><a href='".route('kelola.gallery.edit', $row)."' class='fw-bold text-success'>Edit</a></li>";
                $del   = "<li class='breadcrumb-item'><a href='".route('kelola.gallery.destroy', $row)."' data-confirm-delete='true' class='fw-bold text-danger'>Hapus</a></li>";
                return $start.$edit.$del.$end;
            })
            ->rawColumns(['gambar', 'aktif', 'action'])
            ->toJson();
    }

    public function index(): View|JsonResponse
    {
        confirmDelete('Hapus Foto', 'Yakin ingin menghapus foto ini?');
        if (request()->ajax()) {
            return $this->data();
        }
        return view('pages.kelola.gallery.index');
    }

    public function create(): View
    {
        return view('pages.kelola.gallery.form', ['gallery' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'judul'   => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'urutan'  => ['nullable', 'integer', 'min:0'],
            'gambar'  => ['required', 'image', 'max:5120'],
        ]);

        try {
            DB::beginTransaction();
            $gallery = Gallery::query()->create([
                'judul'      => $request->judul,
                'keterangan' => $request->keterangan,
                'urutan'     => $request->input('urutan', 0),
                'is_active'  => $request->boolean('is_active'),
            ]);
            $gallery->addMediaFromRequest('gambar')->toMediaCollection('galeri');
            DB::commit();
            toast()->success('Berhasil!', 'Foto galeri berhasil disimpan.');
            return redirect()->route('kelola.gallery.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal!', $e->getMessage());
            return back()->withInput();
        }
    }

    public function edit(Gallery $gallery): View
    {
        return view('pages.kelola.gallery.form', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $request->validate([
            'judul'      => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:500'],
            'urutan'     => ['nullable', 'integer', 'min:0'],
            'gambar'     => ['nullable', 'image', 'max:5120'],
        ]);

        try {
            DB::beginTransaction();
            $gallery->update([
                'judul'      => $request->judul,
                'keterangan' => $request->keterangan,
                'urutan'     => $request->input('urutan', $gallery->urutan),
                'is_active'  => $request->boolean('is_active'),
            ]);
            if ($request->hasFile('gambar')) {
                $gallery->clearMediaCollection('galeri');
                $gallery->addMediaFromRequest('gambar')->toMediaCollection('galeri');
            }
            DB::commit();
            toast()->success('Berhasil!', 'Foto galeri berhasil diperbarui.');
            return redirect()->route('kelola.gallery.index');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal!', $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $gallery->delete();
            DB::commit();
            toast()->success('Berhasil!', 'Foto galeri berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            toast()->error('Gagal!', $e->getMessage());
        }
        return redirect()->route('kelola.gallery.index');
    }
}
