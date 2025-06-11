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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->string('lokasi');
            $table->date('tanggal');
            $table->enum('status', ['hilang', 'ditemukan'])->default('hilang');
            $table->string('foto')->nullable(); // path gambar
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // pelapor
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
