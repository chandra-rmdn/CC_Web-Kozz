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
        Schema::create('kos_peraturan', function (Blueprint $table) {
            $table->unsignedBigInteger('kos_id');
            $table->unsignedBigInteger('peraturan_id')->index('kos_peraturan_peraturan_id_foreign');

            $table->primary(['kos_id', 'peraturan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos_peraturan');
    }
};
