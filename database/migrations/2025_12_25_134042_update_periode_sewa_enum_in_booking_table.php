<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Update ENUM values di table booking
        \DB::statement("
        ALTER TABLE booking 
        MODIFY COLUMN periode_sewa 
        ENUM('harian', 'mingguan', 'bulanan', '3_bulanan', '6_bulanan', 'tahunan') 
        DEFAULT 'bulanan'
    ");
    }

    public function down()
    {
        // Rollback ke ENUM lama
        \DB::statement("
        ALTER TABLE booking 
        MODIFY COLUMN periode_sewa 
        ENUM('harian', 'bulanan', 'tahunan') 
        DEFAULT 'bulanan'
    ");
    }
};
