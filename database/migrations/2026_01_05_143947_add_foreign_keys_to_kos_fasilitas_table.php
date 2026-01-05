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
        Schema::table('kos_fasilitas', function (Blueprint $table) {
            $table->foreign(['fasilitas_id'])->references(['id'])->on('fasilitas')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['kos_id'])->references(['id'])->on('kos')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kos_fasilitas', function (Blueprint $table) {
            $table->dropForeign('kos_fasilitas_fasilitas_id_foreign');
            $table->dropForeign('kos_fasilitas_kos_id_foreign');
        });
    }
};
