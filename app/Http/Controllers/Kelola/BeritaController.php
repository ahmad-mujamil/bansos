<?php

namespace App\Http\Controllers\Kelola;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBeritaRequest;
use App\Http\Requests\UpdateBeritaRequest;
use App\Models\Berita;
use App\Models\KategoriBerita;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class BeritaController extends Controller
{
    private function data(): JsonResponse
    {
        $data = Berita::query()
            ->select('berita.*')
            ->addSelect('kategori_berita.nama as joined_kategori_nama')
            ->leftJoin('kategori_berita', 'berita.kategori_berita_id', '=', 'kategori_berita.id')
            ->orderByDesc('berita.published_at');

        return DataTables::eloquent($data)
            ->orderColumn('kategori', 'kategori_berita.nama $1')
            ->addColumn('gambar', function (Berita $row) {
                $url = $row->getFirstMediaUrl('featured', 'thumb');
                if ($url === '') {
                    $url = $row->getFirstMediaUrl('featured');
                }
                if ($url === '') {
                    return '<span class="text-muted">—</span>';
                }

                return '<img src="'.e($url).'" alt="" class="rounded" style="max-height:48px;max-width:72px;object-fit:cover">';
            })
            ->addColumn('kategori', fn (Berita $row) => e($row->joined_kategori_nama ?? '—'))
            ->addColumn('status', function (Berita $row) {
                return $row->isPublished()
                    ? '<span class="badge bg-outline-success">Terbit</span>'
                    : '<span class="badge bg-outline-secondary">Draft</span>';
            })
            ->addColumn('published_at', fn (Berita $row) => $row->published_at?->translatedFormat('d M Y H:i') ?? '—')
            ->addColumn('action', function (Berita $row) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = '</ul></nav>';

                $delete = "<li class='breadcrumb-item'><a href='".route('kelola.berita.destroy', $row)."' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='".route('kelola.berita.edit', $row)."' title='Edit Data'
                        class='fw-bold text-success'>Edit</a></li>";

                return $navActionStart.$edit.$delete.$navActionEnd;
            })
            ->rawColumns(['action', 'gambar', 'status'])
            ->toJson();
    }

    public function index(): View|JsonResponse
    {
        confirmDelete('Delete Data', 'Are you sure you want to delete?');
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.kelola.berita.index');
    }

    public function create(): View
    {
        return view('pages.kelola.berita.form', [
            'berita' => null,
            'kategoriBeritas' => KategoriBerita::query()->orderBy('nama')->get(),
        ]);
    }

    public function store(StoreBeritaRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $validated = $request->safe()->only(['judul', 'kategori_berita_id', 'ringkasan', 'konten']);
            $berita = Berita::query()->create([
                ...$validated,
                'slug' => Berita::generateUniqueSlug($validated['judul']),
                'published_at' => $request->boolean('terbit') ? now() : null,
                'user_id' => $request->user()->id,
            ]);
            $berita->addMediaFromRequest('gambar')->toMediaCollection('featured');
            DB::commit();
            toast()->success('Yeeayy !!', 'Berita berhasil disimpan');

            return redirect()->route('kelola.berita.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function edit(Berita $berita): View
    {
        return view('pages.kelola.berita.form', [
            'berita' => $berita,
            'kategoriBeritas' => KategoriBerita::query()->orderBy('nama')->get(),
        ]);
    }

    public function update(UpdateBeritaRequest $request, Berita $berita): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $validated = $request->safe()->only(['judul', 'kategori_berita_id', 'ringkasan', 'konten']);
            $berita->update([
                ...$validated,
                'published_at' => $request->boolean('terbit')
                    ? ($berita->published_at ?? now())
                    : null,
            ]);
            if ($request->hasFile('gambar')) {
                $berita->clearMediaCollection('featured');
                $berita->addMediaFromRequest('gambar')->toMediaCollection('featured');
            }
            DB::commit();
            toast()->success('Yeeayy !!', 'Berita berhasil diperbarui');

            return redirect()->route('kelola.berita.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function destroy(Berita $berita): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $berita->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Berita berhasil dihapus');

            return redirect()->route('kelola.berita.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return redirect()->route('kelola.berita.index');
        }
    }
}
