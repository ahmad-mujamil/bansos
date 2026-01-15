<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perorangan_detail', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nik', 16)->unique();
            $table->string('nama');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->text('alamat');
            $table->foreignUuid('desa_id')->constrained('desa')->onDelete('cascade');
            $table->string('pekerjaan')->nullable();
            $table->string('jenis_usaha')->nullable();
            $table->decimal('penghasilan', 15, 2)->nullable();
            $table->boolean('status_dtks')->default(false);
            $table->integer('jumlah_tanggungan')->default(0);
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perorangan_detail');
    }
};
