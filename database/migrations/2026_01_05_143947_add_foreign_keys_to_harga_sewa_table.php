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
        Schema::table('harga_sewa', function (Blueprint $table) {
            $table->foreign(['kamar_id'])->references(['id'])->on('kamar')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('harga_sewa', function (Blueprint $table) {
            $table->dropForeign('harga_sewa_kamar_id_foreign');
        });
    }
};
