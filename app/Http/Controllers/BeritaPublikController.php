<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\View\View;

class BeritaPublikController extends Controller
{
    public function index(): View
    {
        $beritas = Berita::query()
            ->published()
            ->with('kategoriBerita')
            ->latest('published_at')
            ->paginate(9);

        return view('pages.berita.publik-index', compact('beritas'));
    }

    public function show(Berita $berita): View
    {
        abort_unless($berita->isPublished(), 404);

        $berita->load('kategoriBerita');

        return view('pages.berita.publik-show', compact('berita'));
    }
}
