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
        Schema::create('kos_fasilitas', function (Blueprint $table) {
            $table->unsignedBigInteger('kos_id');
            $table->unsignedBigInteger('fasilitas_id')->index('kos_fasilitas_fasilitas_id_foreign');

            $table->primary(['kos_id', 'fasilitas_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos_fasilitas');
    }
};
