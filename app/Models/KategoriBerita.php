<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriBerita extends Model
{
    /** @use HasFactory<\Database\Factories\KategoriBeritaFactory> */
    use HasFactory, HasUuids;

    protected $table = 'kategori_berita';

    protected $fillable = [
        'nama',
    ];

    public function berita(): HasMany
    {
        return $this->hasMany(Berita::class, 'kategori_berita_id');
    }
}
