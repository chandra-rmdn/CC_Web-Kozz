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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('kos_id')->index('foto_kos_kos_id_foreign');
            $table->enum('tipe', ['bangunan', 'kamar'])->nullable();
            $table->string('path_foto')->nullable();
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
