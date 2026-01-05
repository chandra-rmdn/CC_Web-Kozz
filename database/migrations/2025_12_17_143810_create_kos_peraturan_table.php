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
            $table->foreignId('kos_id')->constrained('kos');
            $table->foreignId('peraturan_id')->constrained('peraturan');
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
