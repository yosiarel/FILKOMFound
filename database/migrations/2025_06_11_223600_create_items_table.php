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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // GUNAKAN 'item_name' KARENA SELURUH APLIKASI SUDAH DISESUAIKAN
            $table->string('item_name'); 
            
            $table->text('description');
            $table->string('location');
            $table->date('found_date');
            $table->string('image')->nullable();

            // GUNAKAN ENUM UNTUK KONSISTENSI DATA DAN LOGIKA APLIKASI
            $table->enum('status', ['found', 'claimed', 'returned'])->default('found');

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