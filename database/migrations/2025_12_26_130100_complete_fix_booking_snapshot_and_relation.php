<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // 1. Tambah kolom snapshot (jika belum ada)
        if (!Schema::hasColumn('booking', 'kamar_snapshot')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->json('kamar_snapshot')->nullable()->after('kamar_id');
            });
        }

        if (!Schema::hasColumn('booking', 'harga_snapshot')) {
            Schema::table('booking', function (Blueprint $table) {
                $table->json('harga_snapshot')->nullable()->after('total_harga');
            });
        }

        // 2. Ubah kamar_id jadi NULLABLE
        DB::statement("ALTER TABLE booking MODIFY kamar_id BIGINT UNSIGNED NULL");

        // 3. Cek dan hapus foreign key yang ada
        $constraints = DB::select("
        SELECT CONSTRAINT_NAME 
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
        WHERE TABLE_NAME = 'booking' 
        AND COLUMN_NAME = 'kamar_id'
        AND CONSTRAINT_NAME != 'PRIMARY'
    ");

        foreach ($constraints as $constraint) {
            try {
                DB::statement("ALTER TABLE booking DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
            } catch (\Exception $e) {
                // Skip jika constraint tidak ada
                continue;
            }
        }

        // 4. Tambah foreign key baru dengan SET NULL
        DB::statement("
        ALTER TABLE booking 
        ADD CONSTRAINT booking_kamar_id_foreign 
        FOREIGN KEY (kamar_id) REFERENCES kamar(id) 
        ON DELETE SET NULL
    ");
    }

    public function down()
    {
        // 1. Hapus foreign key
        DB::statement("ALTER TABLE booking DROP FOREIGN KEY booking_kamar_id_foreign");

        // 2. Ubah kembali ke NOT NULL
        DB::statement("ALTER TABLE booking MODIFY kamar_id BIGINT UNSIGNED NOT NULL");

        // 3. Tambah foreign key tanpa SET NULL
        DB::statement("
            ALTER TABLE booking 
            ADD CONSTRAINT booking_kamar_id_foreign 
            FOREIGN KEY (kamar_id) REFERENCES kamar(id)
        ");

        // 4. Hapus kolom snapshot
        Schema::table('booking', function (Blueprint $table) {
            $table->dropColumn(['kamar_snapshot', 'harga_snapshot']);
        });
    }
};