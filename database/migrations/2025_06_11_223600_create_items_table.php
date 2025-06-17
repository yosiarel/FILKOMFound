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

        // Kolom untuk relasi ke tabel 'users'
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // Kolom-kolom dari form
        $table->string('name'); 
        $table->text('description')->nullable();
        $table->date('found_date');
        $table->string('location');
        $table->string('image')->nullable();
        $table->string('status')->default('Belum Dikembalikan'); // Status default

        // Kolom created_at dan updated_at
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
