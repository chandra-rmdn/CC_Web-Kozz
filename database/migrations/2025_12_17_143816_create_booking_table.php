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
            $table->id();
            $table->foreignId('user_id')->constrained('users');   // penyewa
            $table->foreignId('kos_id')->constrained('kos');
            $table->foreignId('kamar_id')->constrained('kamar');
            $table->date('tanggal_checkin')->nullable();
            $table->string('kode_checkin', 50)->unique()->nullable();
            $table->integer('durasi_sewa')->nullable();
            $table->enum('periode_sewa', ['harian', 'bulanan', 'tahunan'])->nullable();
            $table->decimal('total_harga', 12, 2)->nullable();
            $table->enum('status', [
                'menunggu_konfirmasi',
                'diterima',
                'ditolak',
                'menunggu_pembayaran',
                'dibatalkan',
                'selesai',
                'telah_keluar'
            ])->nullable();
            $table->text('catatan_penyewa')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
