<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_berita', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama')->unique();
            $table->timestamps();
        });

        $now = now();
        $namaToId = [];

        foreach (['Umum', 'Penyaluran', 'Kesehatan', 'Pendidikan'] as $nama) {
            $id = (string) Str::uuid();
            DB::table('kategori_berita')->insert([
                'id' => $id,
                'nama' => $nama,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $namaToId[$nama] = $id;
        }

        Schema::table('berita', function (Blueprint $table) {
            $table->foreignUuid('kategori_berita_id')
                ->nullable()
                ->after('slug')
                ->constrained('kategori_berita')
                ->nullOnDelete();
        });

        $distinctKategori = DB::table('berita')
            ->select('kategori')
            ->whereNotNull('kategori')
            ->where('kategori', '!=', '')
            ->distinct()
            ->pluck('kategori');

        foreach ($distinctKategori as $namaKategori) {
            if (! array_key_exists($namaKategori, $namaToId)) {
                $id = (string) Str::uuid();
                DB::table('kategori_berita')->insert([
                    'id' => $id,
                    'nama' => $namaKategori,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                $namaToId[$namaKategori] = $id;
            }

            DB::table('berita')
                ->where('kategori', $namaKategori)
                ->update(['kategori_berita_id' => $namaToId[$namaKategori]]);
        }

        $defaultId = $namaToId['Umum'] ?? DB::table('kategori_berita')->where('nama', 'Umum')->value('id');
        if ($defaultId !== null) {
            DB::table('berita')->whereNull('kategori_berita_id')->update(['kategori_berita_id' => $defaultId]);
        }

        Schema::table('berita', function (Blueprint $table) {
            $table->dropColumn('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('berita', function (Blueprint $table) {
            $table->string('kategori')->nullable()->after('slug');
        });

        $map = DB::table('kategori_berita')->pluck('nama', 'id');

        foreach (DB::table('berita')->whereNotNull('kategori_berita_id')->cursor() as $row) {
            DB::table('berita')->where('id', $row->id)->update([
                'kategori' => $map[$row->kategori_berita_id] ?? 'Umum',
            ]);
        }

        Schema::table('berita', function (Blueprint $table) {
            $table->dropForeign(['kategori_berita_id']);
            $table->dropColumn('kategori_berita_id');
        });

        Schema::dropIfExists('kategori_berita');
    }
};
