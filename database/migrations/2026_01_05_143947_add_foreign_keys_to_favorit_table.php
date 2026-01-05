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
        Schema::table('favorit', function (Blueprint $table) {
            $table->foreign(['kos_id'])->references(['id'])->on('kos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorit', function (Blueprint $table) {
            $table->dropForeign('favorit_kos_id_foreign');
            $table->dropForeign('favorit_user_id_foreign');
        });
    }
};
