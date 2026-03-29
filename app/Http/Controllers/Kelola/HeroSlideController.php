<?php

namespace App\Http\Controllers\Kelola;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHeroSlideRequest;
use App\Http\Requests\UpdateHeroSlideRequest;
use App\Models\HeroSlide;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class HeroSlideController extends Controller
{
    private function data(): JsonResponse
    {
        $data = HeroSlide::query()->ordered();

        return DataTables::eloquent($data)
            ->addColumn('gambar', function (HeroSlide $row) {
                $url = $row->getFirstMediaUrl('hero', 'thumb');
                if ($url === '') {
                    $url = $row->getFirstMediaUrl('hero');
                }
                if ($url === '') {
                    return '<span class="text-muted">—</span>';
                }

                return '<img src="'.e($url).'" alt="" class="rounded" style="max-height:48px;max-width:80px;object-fit:cover">';
            })
            ->addColumn('aktif', function (HeroSlide $row) {
                return $row->is_active
                    ? '<span class="badge bg-outline-success">Ya</span>'
                    : '<span class="badge bg-outline-secondary">Tidak</span>';
            })
            ->addColumn('action', function (HeroSlide $row) {
                $navActionStart = '<nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb"><ul class="breadcrumb pt-0">';
                $navActionEnd = '</ul></nav>';

                $delete = "<li class='breadcrumb-item'><a href='".route('kelola.slider.destroy', $row)."' data-confirm-delete='true'
                        title='Hapus Data' class='fw-bold text-danger'>Delete</a></li>";

                $edit = "<li class='breadcrumb-item'><a href='".route('kelola.slider.edit', $row)."' title='Edit Data'
                        class='fw-bold text-success'>Edit</a></li>";

                return $navActionStart.$edit.$delete.$navActionEnd;
            })
            ->rawColumns(['action', 'gambar', 'aktif'])
            ->toJson();
    }

    public function index(): View|JsonResponse
    {
        confirmDelete('Delete Data', 'Are you sure you want to delete?');
        if (request()->ajax()) {
            return $this->data();
        }

        return view('pages.kelola.hero-slide.index');
    }

    public function create(): View
    {
        return view('pages.kelola.hero-slide.form', ['heroSlide' => null]);
    }

    public function store(StoreHeroSlideRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $validated = $request->safe()->only(['kategori', 'judul', 'judul_sorot', 'subtitle', 'urutan']);
            $heroSlide = HeroSlide::query()->create([
                ...$validated,
                'urutan' => $validated['urutan'] ?? 0,
                'is_active' => $request->boolean('is_active'),
            ]);
            $heroSlide->addMediaFromRequest('gambar')->toMediaCollection('hero');
            DB::commit();
            toast()->success('Yeeayy !!', 'Slide berhasil disimpan');

            return redirect()->route('kelola.slider.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function edit(HeroSlide $heroSlide): View
    {
        return view('pages.kelola.hero-slide.form', compact('heroSlide'));
    }

    public function update(UpdateHeroSlideRequest $request, HeroSlide $heroSlide): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $validated = $request->safe()->only(['kategori', 'judul', 'judul_sorot', 'subtitle', 'urutan']);
            $heroSlide->update([
                ...$validated,
                'urutan' => $validated['urutan'] ?? $heroSlide->urutan,
                'is_active' => $request->boolean('is_active'),
            ]);
            if ($request->hasFile('gambar')) {
                $heroSlide->clearMediaCollection('hero');
                $heroSlide->addMediaFromRequest('gambar')->toMediaCollection('hero');
            }
            DB::commit();
            toast()->success('Yeeayy !!', 'Slide berhasil diperbarui');

            return redirect()->route('kelola.slider.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return back()->withInput();
        }
    }

    public function destroy(HeroSlide $heroSlide): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $heroSlide->delete();
            DB::commit();
            toast()->success('Yeeayy !!', 'Slide berhasil dihapus');

            return redirect()->route('kelola.slider.index');
        } catch (\Throwable $th) {
            DB::rollBack();
            toast()->error('Oppss !!', $th->getMessage());

            return redirect()->route('kelola.slider.index');
        }
    }
}
