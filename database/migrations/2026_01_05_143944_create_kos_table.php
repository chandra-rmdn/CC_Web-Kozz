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
        Schema::create('kos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('kos_user_id_foreign');
            $table->string('nama_kos', 100)->nullable();
            $table->enum('tipe_kos', ['putra', 'putri', 'campur'])->nullable();
            $table->text('deskripsi')->nullable();
            $table->double('mean_rating')->default(0);
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
            $table->integer('total_kamar')->default(0);
            $table->integer('kamar_tersedia')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kos');
    }
};
