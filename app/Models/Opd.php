<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Organisasi;

class Opd extends Model
{
    use HasUuids;
    protected $table = 'opd';
    protected $keyType = 'string';

    public function user(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function organisasi(): HasMany
    {
        return $this->hasMany(Organisasi::class);
    }

    public function getSingkatanAttribute(): string
    {
        $stopwords = ['dan', 'di', 'ke', 'atau', 'serta', 'pada'];
        $words = preg_split('/\s+/', trim((string) $this->nama)) ?: [];
        $initials = '';
        foreach ($words as $word) {
            if ($word === '' || in_array(mb_strtolower($word), $stopwords, true)) {
                continue;
            }
            $initials .= mb_strtoupper(mb_substr($word, 0, 1));
        }
        return $initials !== '' ? $initials : (string) $this->nama;
    }
}

