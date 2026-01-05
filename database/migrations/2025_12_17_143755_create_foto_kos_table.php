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
        Schema::create('foto_kos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kos_id')->constrained('kos');
            $table->enum('tipe', ['bangunan', 'kamar'])->nullable();
            $table->longText('path_foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foto_kos');
    }
};
