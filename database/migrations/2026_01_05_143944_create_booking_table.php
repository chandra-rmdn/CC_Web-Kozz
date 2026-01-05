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
        Schema::create('booking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('booking_user_id_foreign');
            $table->unsignedBigInteger('kos_id')->index('booking_kos_id_foreign');
            $table->unsignedBigInteger('kamar_id')->nullable()->index('booking_kamar_id_foreign');
            $table->json('kamar_snapshot')->nullable();
            $table->date('tanggal_checkin')->nullable();
            $table->string('kode_checkin', 50)->nullable()->unique();
            $table->integer('durasi_sewa')->nullable();
            $table->enum('periode_sewa', ['harian', 'mingguan', 'bulanan', '3_bulanan', '6_bulanan', 'tahunan'])->nullable()->default('bulanan');
            $table->decimal('total_harga', 12)->nullable();
            $table->json('harga_snapshot')->nullable();
            $table->enum('status', ['menunggu_konfirmasi', 'diterima', 'ditolak', 'menunggu_pembayaran', 'dibatalkan', 'selesai'])->nullable();
            $table->text('catatan_penyewa')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
