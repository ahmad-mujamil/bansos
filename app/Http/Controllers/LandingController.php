<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\HeroSlide;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function __invoke(): View
    {
        $heroSlides = HeroSlide::query()
            ->active()
            ->ordered()
            ->get();

        $beritaTerbaru = Berita::query()
            ->published()
            ->with('kategoriBerita')
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('pages.landing', compact('beritaTerbaru', 'heroSlides'));
    }
}
