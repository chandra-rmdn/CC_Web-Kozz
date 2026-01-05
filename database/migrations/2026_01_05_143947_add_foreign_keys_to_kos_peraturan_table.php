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
        Schema::table('kos_peraturan', function (Blueprint $table) {
            $table->foreign(['kos_id'])->references(['id'])->on('kos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['peraturan_id'])->references(['id'])->on('peraturan')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kos_peraturan', function (Blueprint $table) {
            $table->dropForeign('kos_peraturan_kos_id_foreign');
            $table->dropForeign('kos_peraturan_peraturan_id_foreign');
        });
    }
};
