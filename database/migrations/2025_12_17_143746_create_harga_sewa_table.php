<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('harga_sewa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kamar_id')->constrained('kamar');
            $table->enum('periode', [
                'harian',
                'mingguan',
                'bulanan',
                '3_bulanan',
                '6_bulanan',
                'tahunan'
            ])->nullable();
            $table->decimal('harga', 12, 2)->nullable();
            $table->decimal('denda_per_hari', 12, 2)->nullable();
            $table->integer('batas_hari_denda')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_sewa');
    }
};
