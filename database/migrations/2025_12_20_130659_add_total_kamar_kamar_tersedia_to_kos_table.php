<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->integer('total_kamar')->default(0)->after('status');
            $table->integer('kamar_tersedia')->default(0)->after('total_kamar');
        });
    }

    public function down()
    {
        Schema::table('kos', function (Blueprint $table) {
            $table->dropColumn(['total_kamar', 'kamar_tersedia']);
        });
    }
};
