<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\KosController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

Route::prefix('api')->group(function () {
    Route::post('/save-local-kos', function (Request $request) {
        DB::beginTransaction();
        try {
            $data = $request->all();

            \Log::info('🎯 Data dari JavaScript:', $data);

            // ========== ✅ UPDATE LOGIC INI ==========
            $isNewKos = false;

            // CEK 1: Jika ada database_id di request → UPDATE KOS YANG ADA
            if (isset($data['database_id']) && !empty($data['database_id'])) {
                $kos = \App\Models\Kos::find($data['database_id']);

                if (!$kos) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kos dengan ID ' . $data['database_id'] . ' tidak ditemukan'
                    ], 404);
                }

                // UPDATE KOS YANG SUDAH ADA
                $kos->update([
                    'nama_kos' => $data['nama_kos'] ?? $kos->nama_kos,
                    'tipe_kos' => $data['tipe_kos'] ?? $kos->tipe_kos,
                    'deskripsi' => $data['deskripsi'] ?? $kos->deskripsi,
                    'total_kamar' => $data['total_rooms'] ?? $kos->total_kamar,
                    'kamar_tersedia' => $data['available_rooms'] ?? $kos->kamar_tersedia,
                    'status' => 'active'
                ]);

                $isNewKos = false;

            } else {
                // CEK 2: Jika TIDAK ada database_id → BUAT KOS BARU
                $existingKos = \App\Models\Kos::where('nama_kos', $data['nama_kos'] ?? '')
                    ->where('user_id', auth()->id() ?? 1)
                    ->first();

                if ($existingKos) {
                    // OPTION A: Tampilkan error (user harus ganti nama)
                    return response()->json([
                        'success' => false,
                        'message' => 'Nama kos "' . ($data['nama_kos'] ?? '') . '" sudah digunakan. Silakan gunakan nama yang berbeda.'
                    ], 400);
                }

                // BUAT KOS BARU
                $kos = \App\Models\Kos::create([
                    'user_id' => auth()->id() ?? 1,
                    'nama_kos' => $data['nama_kos'] ?? 'Kos Tanpa Nama',
                    'tipe_kos' => $data['tipe_kos'] ?? 'campur',
                    'deskripsi' => $data['deskripsi'] ?? '',
                    'total_kamar' => $data['total_rooms'] ?? 0,
                    'kamar_tersedia' => $data['available_rooms'] ?? 0,
                    'status' => 'active'
                ]);

                $isNewKos = true;
            }

            $kosId = $kos->id;

            // 2. UPDATE/SIMPAN ALAMAT
            $alamat = \App\Models\AlamatKos::updateOrCreate(
                ['kos_id' => $kosId],
                [
                    'alamat' => $data['address']['alamat'] ?? '',
                    'provinsi' => $data['address']['provinsi'] ?? '',
                    'kabupaten' => $data['address']['kabupaten'] ?? '',
                    'kecamatan' => $data['address']['kecamatan'] ?? '',
                    'catatan_alamat' => $data['address']['catatan'] ?? '',
                    'lat' => $data['address']['lat'] ?? null,
                    'lon' => $data['address']['lon'] ?? null
                ]
            );

            // 3. SIMPAN FASILITAS (sync - replace all)
            if (isset($data['fasilitas']) && is_array($data['fasilitas'])) {
                $fasilitasIds = [];

                foreach ($data['fasilitas'] as $kategori => $fasilitasList) {
                    if (is_array($fasilitasList)) {
                        foreach ($fasilitasList as $fasilitasNama) {
                            $fasilitas = \App\Models\Fasilitas::firstOrCreate(
                                ['nama_fasilitas' => $fasilitasNama],
                                ['kategori' => $kategori]
                            );
                            $fasilitasIds[] = $fasilitas->id;
                        }
                    }
                }
                $kos->fasilitas()->sync($fasilitasIds);
            }

            // 4. SIMPAN PERATURAN (sync - replace all)
            if (isset($data['rules']) && is_array($data['rules'])) {
                $peraturanIds = [];
                foreach ($data['rules'] as $peraturanNama) {
                    $peraturan = \App\Models\Peraturan::firstOrCreate(
                        ['nama_peraturan' => $peraturanNama]
                    );
                    $peraturanIds[] = $peraturan->id;
                }
                $kos->peraturan()->sync($peraturanIds);
            }

            // 5. HAPUS FOTO LAMA DAN SIMPAN FOTO BARU
            if (isset($data['images'])) {
                \App\Models\FotoKos::where('kos_id', $kosId)->delete();

                if (isset($data['images']['bangunan']) && is_array($data['images']['bangunan'])) {
                    foreach ($data['images']['bangunan'] as $foto) {
                        \App\Models\FotoKos::create([
                            'kos_id' => $kosId,
                            'tipe' => 'bangunan',
                            'path_foto' => $foto
                        ]);
                    }
                }

                if (isset($data['images']['kamar']) && is_array($data['images']['kamar'])) {
                    foreach ($data['images']['kamar'] as $foto) {
                        \App\Models\FotoKos::create([
                            'kos_id' => $kosId,
                            'tipe' => 'kamar',
                            'path_foto' => $foto
                        ]);
                    }
                }
            }

            // ========== ✅ BAGIAN YANG DIPERBAIKI: KAMAR & BOOKING ==========
            // 6. HANDLE KAMAR LAMA DAN BOOKING
            $kamarLama = \App\Models\Kamar::where('kos_id', $kosId)->get();

            foreach ($kamarLama as $kamar) {
                // A. BACKUP KE BOOKING SEBELUM HAPUS
                $hargaData = \App\Models\HargaSewa::where('kamar_id', $kamar->id)->get();

                \DB::table('booking')
                    ->where('kamar_id', $kamar->id)
                    ->whereNull('deleted_at')
                    ->update([
                        'kamar_id' => null,  // PUTUS HUBUNGAN
                        'kamar_snapshot' => json_encode([
                            'nama_kamar' => $kamar->nama_kamar,
                            'lantai' => $kamar->lantai,
                            'ukuran_kamar' => $kamar->ukuran_kamar,
                            'status' => $kamar->status,
                            'harga_sewa' => $hargaData->map(function ($h) {
                                return [
                                    'periode' => $h->periode,
                                    'harga' => $h->harga,
                                    'denda_per_hari' => $h->denda_per_hari
                                ];
                            })->toArray(),
                            'backup_date' => now()->toDateTimeString()
                        ])
                    ]);

                // B. HAPUS HARGA SEWA
                \App\Models\HargaSewa::where('kamar_id', $kamar->id)->delete();

                // C. HAPUS KAMAR (AMAN KARENA SUDAH TIDAK ADA RELASI)
                $kamar->delete();
            }

            // 7. BUAT KAMAR BARU
            if (isset($data['room_details']) && is_array($data['room_details'])) {
                foreach ($data['room_details'] as $kamar) {
                    $kamarRecord = \App\Models\Kamar::create([
                        'kos_id' => $kosId,
                        'nama_kamar' => $kamar['nomor'] ?? 'Kamar',
                        'lantai' => $kamar['lantai'] ?? 1,
                        'ukuran_kamar' => isset($data['size']) ?
                            ($data['size']['custom_w'] ?? '3') . 'x' . ($data['size']['custom_l'] ?? '4') : '3x4',
                        'status' => ($kamar['terisi'] ?? false) ? 'terisi' : 'tersedia'
                    ]);

                    $kamarId = $kamarRecord->id;

                    // SIMPAN HARGA
                    if (isset($data['price'])) {
                        $priceData = $data['price'];
                        $activePeriods = [];

                        if (($priceData['daily'] ?? 0) > 0)
                            $activePeriods['harian'] = $priceData['daily'];
                        if (($priceData['weekly'] ?? 0) > 0)
                            $activePeriods['mingguan'] = $priceData['weekly'];
                        if (($priceData['monthly'] ?? 0) > 0)
                            $activePeriods['bulanan'] = $priceData['monthly'];
                        if (($priceData['three_monthly'] ?? 0) > 0)
                            $activePeriods['3_bulanan'] = $priceData['three_monthly'];
                        if (($priceData['six_monthly'] ?? 0) > 0)
                            $activePeriods['6_bulanan'] = $priceData['six_monthly'];
                        if (($priceData['yearly'] ?? 0) > 0)
                            $activePeriods['tahunan'] = $priceData['yearly'];

                        foreach ($activePeriods as $periode => $harga) {
                            \App\Models\HargaSewa::create([
                                'kamar_id' => $kamarId,
                                'periode' => $periode,
                                'harga' => $harga,
                                'denda_per_hari' => ($priceData['set_fine'] ?? false) ?
                                    ($priceData['fine_amount'] ?? 0) : 0,
                                'batas_hari_denda' => ($priceData['set_fine'] ?? false) ?
                                    ($priceData['fine_limit'] ?? 0) : 0
                            ]);
                        }

                        \Log::info("💵 Harga disimpan untuk kamar $kamarId:", [
                            'active_periods' => $activePeriods
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $kos->wasRecentlyCreated ?
                    '✅ Data kos baru berhasil disimpan!' :
                    '✅ Data kos berhasil diperbarui!',
                'kos_id' => $kosId,
                'is_new' => $kos->wasRecentlyCreated,
                'action' => $kos->wasRecentlyCreated ? 'create' : 'update'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('❌ Error menyimpan kos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    });

    // HAPUS KOS DAN SEMUA DATA TERKAIT
    // SOFT DELETE KOS (CLEAN UI)
    Route::delete('/delete-kos/{id}', function ($id) {
        try {
            $kos = \App\Models\Kos::find($id);

            if (!$kos) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kos tidak ditemukan'
                ], 404);
            }

            // CEK APAKAH ADA BOOKING AKTIF
            $activeBookings = \App\Models\Booking::where('kos_id', $id)
                ->whereIn('status', ['diterima', 'check_in', 'menunggu_konfirmasi'])
                ->exists();

            if ($activeBookings) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa hapus kos karena masih ada penyewa aktif'
                ], 400);
            }

            // SOFT DELETE SAJA (tidak hapus data child)
            $kosName = $kos->nama_kos;
            $kos->delete(); // ← Ini akan set deleted_at, bukan hapus fisik

            return response()->json([
                'success' => true,
                'message' => "Kos '{$kosName}' berhasil dihapus",
                'note' => 'Data masih ada di database dengan status soft deleted'
            ]);

        } catch (\Exception $e) {
            \Log::error('❌ Error menghapus kos: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kos: ' . $e->getMessage()
            ], 500);
        }
    });

    // ROUTE 2: Hapus berdasarkan nama (BARU)
    Route::delete('/delete-kos-by-name', function (Request $request) {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $namaKos = $data['nama_kos'] ?? '';
            $userId = auth()->id() ?? 1;

            if (empty($namaKos)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nama kos tidak boleh kosong'
                ], 400);
            }

            // Cari kos berdasarkan nama
            $kos = \App\Models\Kos::where('nama_kos', $namaKos)
                ->where('user_id', $userId)
                ->first();

            if (!$kos) {
                return response()->json([
                    'success' => false,
                    'message' => "Kos '{$namaKos}' tidak ditemukan di database"
                ], 404);
            }

            $kosId = $kos->id;

            // HAPUS SEMUA DATA TERKAIT
            // a. Hapus harga sewa
            $kamarIds = \App\Models\Kamar::where('kos_id', $kosId)->pluck('id');
            if ($kamarIds->count() > 0) {
                \App\Models\HargaSewa::whereIn('kamar_id', $kamarIds)->delete();
            }

            // b. Hapus kamar
            \App\Models\Kamar::where('kos_id', $kosId)->delete();

            // c. Hapus foto
            \App\Models\FotoKos::where('kos_id', $kosId)->delete();

            // d. Hapus relasi many-to-many
            \App\Models\KosFasilitas::where('kos_id', $kosId)->delete();
            \App\Models\KosPeraturan::where('kos_id', $kosId)->delete();

            // e. Hapus alamat
            \App\Models\AlamatKos::where('kos_id', $kosId)->delete();

            // 2. HAPUS KOS UTAMA
            $kos->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "{$namaKos} berhasil dihapus",
                'deleted_kos_id' => $kosId,
                'tables_affected' => [
                    'harga_sewa',
                    'kamar',
                    'foto_kos',
                    'kos_fasilitas',
                    'kos_peraturan',
                    'alamat_kos',
                    'kos'
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('❌ Error menghapus kos by name: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kos: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    });



    // ========== ADMIN BOOKING MANAGEMENT ==========

    // 1. Ambil semua booking untuk admin
    Route::get('/admin/bookings', function (Request $request) {
        try {
            $userId = auth()->id();
            $status = $request->query('status');

            if (!$userId) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $query = \DB::table('booking')
                ->join('kos', 'booking.kos_id', '=', 'kos.id')
                ->leftJoin('kamar', 'booking.kamar_id', '=', 'kamar.id')
                ->join('users', 'booking.user_id', '=', 'users.id')
                ->where('kos.user_id', $userId)
                ->whereNull('booking.deleted_at');

            if ($status && $status !== 'semua') {
                if ($status === 'ditolak') {
                    $query->whereIn('booking.status', ['ditolak', 'dibatalkan']);
                } else {
                    $query->where('booking.status', $status);
                }
            }

            $bookings = $query
                ->select(
                    'booking.*',
                    'kos.nama_kos',
                    'kos.tipe_kos',
                    'kamar.nama_kamar',
                    'kamar.lantai',
                    'kamar.status as status_kamar',
                    'users.name as nama_penyewa',
                    'users.email',
                    'users.no_hp',
                    \DB::raw("COALESCE(
                    JSON_UNQUOTE(JSON_EXTRACT(booking.kamar_snapshot, '$.nama_kamar')),
                    kamar.nama_kamar,
                    'Kamar'
                ) as nama_kamar_display"),
                    \DB::raw("COALESCE(
                    JSON_UNQUOTE(JSON_EXTRACT(booking.kamar_snapshot, '$.lantai')),
                    kamar.lantai,
                    '-'
                ) as lantai_display"),
                    \DB::raw("CASE 
                    WHEN booking.kamar_id IS NULL THEN 'snapshot'
                    WHEN booking.kamar_id IS NOT NULL AND kamar.id IS NOT NULL THEN 'live'
                    ELSE 'unknown'
                END as data_source")
                )
                ->orderBy('booking.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'bookings' => $bookings,
                'status_filter' => $status
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching admin bookings:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // 2. Terima booking (update status + update kamar)
    Route::post('/admin/booking/{id}/accept', function ($id) {
        \DB::beginTransaction();
        try {
            $booking = \DB::table('booking')->find($id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan'
                ], 404);
            }

            // Cek apakah booking milik kos admin
            $kos = \DB::table('kos')
                ->where('id', $booking->kos_id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$kos) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Update status booking
            \DB::table('booking')
                ->where('id', $id)
                ->update([
                    'status' => 'menunggu_pembayaran',
                ]);

            /// Hanya update status kamar jika kamar_id masih ada
            if ($booking->kamar_id) {
                \DB::table('kamar')
                    ->where('id', $booking->kamar_id)
                    ->update(['status' => 'terisi']);

                $jumlahTersedia = \DB::table('kamar')
                    ->where('kos_id', $booking->kos_id)
                    ->where('status', 'tersedia')
                    ->count();

                \DB::table('kos')
                    ->where('id', $booking->kos_id)
                    ->update(['kamar_tersedia' => $jumlahTersedia]);
            }

            \DB::commit();

            \Log::info('Booking accepted by admin:', [
                'booking_id' => $id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil diterima'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error accepting booking:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // 3. Tolak booking
    Route::post('/admin/booking/{id}/reject', function ($id) {
        try {
            $booking = \DB::table('booking')->find($id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan'
                ], 404);
            }

            // Cek apakah booking milik kos admin
            $kos = \DB::table('kos')
                ->where('id', $booking->kos_id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$kos) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Update status booking
            \DB::table('booking')
                ->where('id', $id)
                ->update([
                    'status' => 'ditolak',
                    // 'update_at' => now()
                ]);
            // Hanya update status kamar jika kamar_id masih ada
            if ($booking->kamar_id) {
                \DB::table('booking')
                    ->where('id', $booking->kamar_id)
                    ->update(['status' => 'tersedia']);
            }

            // Status kamar tetap tersedia (karena booking ditolak)

            \Log::info('Booking rejected by admin:', [
                'booking_id' => $id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil ditolak'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error rejecting booking:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // 4. Batalkan booking (admin side)
    Route::post('/admin/booking/{id}/cancel', function ($id) {
        \DB::beginTransaction();
        try {
            $booking = \DB::table('booking')->find($id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan'
                ], 404);
            }

            // Cek apakah booking milik kos admin
            $kos = \DB::table('kos')
                ->where('id', $booking->kos_id)
                ->where('user_id', auth()->id())
                ->first();

            if (!$kos) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }

            // Update status booking
            \DB::table('booking')
                ->where('id', $id)
                ->update([
                    'status' => 'dibatalkan',
                    // 'update_at' => now()
                ]);
            // Hanya update status kamar jika kamar_id masih ada
            if ($booking->kamar_id) {
                \DB::table('booking')
                    ->where('id', $booking->kamar_id)
                    ->update(['status' => 'dibatalkan']);
            }

            // Update status kamar kembali ke tersedia
            \DB::table('kamar')
                ->where('id', $booking->kamar_id)
                ->update(['status' => 'tersedia']);

            \DB::commit();

            \Log::info('Booking canceled by admin:', [
                'booking_id' => $id,
                'admin_id' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error canceling booking:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    Route::post('/admin/booking/{id}/checkout', function ($id) {
        DB::beginTransaction();
        try {
            $booking = DB::table('booking')->find($id);

            // Validasi: hanya booking dengan status 'selesai' yang bisa checkout
            if ($booking->status !== 'selesai') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya booking dengan status selesai yang bisa checkout'
                ], 400);
            }

            // 1. Update status kamar jadi 'tersedia'
            DB::table('kamar')
                ->where('id', $booking->kamar_id)
                ->update(['status' => 'tersedia']);

            // 2. Hitung ulang kamar tersedia untuk kos ini
            $jumlahTersedia = DB::table('kamar')
                ->where('kos_id', $booking->kos_id)
                ->where('status', 'tersedia')
                ->count();

            // 3. Update tabel kos
            DB::table('kos')
                ->where('id', $booking->kos_id)
                ->update(['kamar_tersedia' => $jumlahTersedia]);

            DB::table('booking')
                ->where('id', $id)
                ->update(['status' => 'telah_keluar']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Penyewa berhasil checkout. Kamar sekarang tersedia.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    });

    // SOFT DELETE untuk riwayat booking
    Route::post('/admin/booking/{id}/delete-history', function ($id) {
        try {
            $booking = DB::table('booking')->find($id);

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan'
                ], 404);
            }

            $allowedStatusToDelete = ['telah_keluar', 'ditolak', 'dibatalkan'];
            if (!in_array($booking->status, $allowedStatusToDelete)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya booking dengan status tertentu yang bisa dihapus'
                ], 400);
            }

            // SOFT DELETE: set deleted_at timestamp
            DB::table('booking')
                ->where('id', $id)
                ->update(['deleted_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Riwayat booking berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus riwayat: ' . $e->getMessage()
            ], 500);
        }
    });

    // 6. Get booking detail untuk modal/detail view
    Route::get('/admin/booking/{id}', function ($id) {
        try {
            $booking = \DB::table('booking')
                ->join('kos', 'booking.kos_id', '=', 'kos.id')
                ->leftJoin('kamar', 'booking.kamar_id', '=', 'kamar.id')
                ->join('users', 'booking.user_id', '=', 'users.id')
                ->leftJoin('alamat_kos', 'kos.id', '=', 'alamat_kos.kos_id')
                ->where('booking.id', $id)
                ->where('kos.user_id', auth()->id())
                ->whereNull('deleted_at')
                ->select(
                    'booking.*',
                    'kos.nama_kos',
                    'kos.tipe_kos',
                    'kos.deskripsi as deskripsi_kos',
                    'kamar.nama_kamar',
                    'kamar.lantai',
                    'kamar.ukuran_kamar',
                    'users.name as nama_penyewa',
                    'users.email',
                    'users.no_hp',
                    'users.jenis_kelamin',
                    'alamat_kos.alamat',
                    'alamat_kos.kecamatan',
                    'alamat_kos.kabupaten',
                    'alamat_kos.provinsi',

                    \DB::raw("COALESCE(
                    JSON_UNQUOTE(JSON_EXTRACT(booking.kamar_snapshot, '$.nama_kamar')),
                    kamar.nama_kamar,
                    'Kamar'
                    ) as nama_kamar"),
                    \DB::raw("COALESCE(
                        JSON_UNQUOTE(JSON_EXTRACT(booking.kamar_snapshot, '$.lantai')),
                        kamar.lantai,
                        '-'
                    ) as lantai"),
                    \DB::raw("COALESCE(
                        JSON_UNQUOTE(JSON_EXTRACT(booking.kamar_snapshot, '$.ukuran_kamar')),
                        kamar.ukuran_kamar,
                        '3x4'
                    ) as ukuran_kamar"),
                    \DB::raw("CASE 
                        WHEN booking.kamar_id IS NULL THEN 'Data dari snapshot'
                        WHEN booking.kamar_id IS NOT NULL AND kamar.id IS NOT NULL THEN 'Data live'
                        ELSE 'Data tidak tersedia'
                    END as data_status")
                )
                ->first();

            if (!$booking) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak ditemukan atau unauthorized'
                ], 404);
            }

            // Ambil foto kos jika ada
            $fotoKos = \DB::table('foto_kos')
                ->where('kos_id', $booking->kos_id)
                ->where('tipe', 'bangunan')
                ->first();

            if ($fotoKos) {
                $booking->foto_kos = $fotoKos->path_foto;
            }

            return response()->json([
                'success' => true,
                'booking' => $booking
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching booking detail:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    Route::get('/api/my-bookings', function () {
        try {
            $userId = auth()->id();

            if (!$userId) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $status = request('status', 'menunggu_konfirmasi');

            $bookings = \DB::table('booking')
                ->join('kos', 'booking.kos_id', '=', 'kos.id')
                ->leftJoin('kamar', 'booking.kamar_id', '=', 'kamar.id')
                ->where('booking.user_id', $userId)
                ->where('booking.status', $status)
                ->whereNull('deleted_at')
                ->select(
                    'booking.*',
                    'kos.nama_kos',
                    'kos.tipe_kos',
                    // Handle both live data and snapshot
                    \DB::raw("COALESCE(
                    JSON_UNQUOTE(JSON_EXTRACT(booking.kamar_snapshot, '$.nama_kamar')),
                    kamar.nama_kamar,
                    'Kamar'
                ) as nama_kamar"),
                    \DB::raw("COALESCE(
                    JSON_UNQUOTE(JSON_EXTRACT(booking.kamar_snapshot, '$.lantai')),
                    kamar.lantai,
                    '-'
                ) as lantai"),
                    \DB::raw("CASE 
                    WHEN booking.kamar_id IS NULL THEN 'snapshot'
                    ELSE 'live'
                END as data_source")
                )
                ->orderBy('booking.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'bookings' => $bookings,
                'count' => $bookings->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching user bookings:', ['error' => $e->getMessage()]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    })->middleware('auth');
});

// GANTI middleware dengan cek manual:
Route::middleware(['auth'])->group(function () {
    Route::get('/api/owner/kos', function () {
        // CEK ROLE MANUAL
        if (auth()->user()->role !== 'pemilik') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $userId = auth()->id();
        $kosList = \App\Models\Kos::with([
            'alamatKos',
            'fasilitas',
            'peraturan',
            'fotoKos',
            'kamar.hargaSewa'
        ])->where('user_id', $userId)->get();

        return response()->json($kosList);
    });

    // Route::prefix('chat')->group(function () {
    //     Route::post('/rooms/get-or-create', [ChatController::class, 'getOrCreateRoom']);
    //     Route::get('/rooms/{room}/messages', [ChatController::class, 'getMessages']);
    //     Route::post('/rooms/{room}/messages', [ChatController::class, 'sendMessage']);
    // });

    Route::prefix('chat')->group(function () {
        Route::get('/rooms', [ChatController::class, 'getRooms']); // GET daftar rooms
        Route::get('/rooms/{room}', [ChatController::class, 'getRoom']);
        Route::post('/rooms/get-or-create', [ChatController::class, 'getOrCreateRoom']); // POST buat room baru
        Route::get('/rooms/{room}/messages', [ChatController::class, 'getMessages']); // GET pesan
        Route::post('/rooms/{room}/messages', [ChatController::class, 'sendMessage']); // POST kirim pesan
    });
});

// Ambil semua kos dari database
Route::get('/api/get-kos', function () {
    $kosList = \App\Models\Kos::with(['alamatKos', 'fasilitas', 'peraturan', 'fotoKos', 'kamar.hargaSewa'])
        ->where('user_id', auth()->id() ?? 1)
        ->get();

    return response()->json($kosList);
});

// routes/web.php
Route::get('/api/get-kos/{id}', function ($id) {
    $kos = \App\Models\Kos::with([
        'alamatKos',
        'fasilitas',
        'peraturan',
        'fotoKos',
        'kamar.hargaSewa'
    ])->find($id);

    if (!$kos) {
        return response()->json(['error' => 'Kos tidak ditemukan'], 404);
    }

    return response()->json($kos);
})->middleware('auth');

Route::get('/debug-alamat/{id}', function ($id) {
    $alamat = \App\Models\AlamatKos::where('kos_id', $id)->first();

    if (!$alamat) {
        return 'Alamat tidak ditemukan untuk kos ID: ' . $id;
    }

    return response()->json([
        'id' => $alamat->id,
        'kos_id' => $alamat->kos_id,
        'alamat' => $alamat->alamat,
        'lat' => $alamat->lat,
        'lon' => $alamat->lon,  // Perhatikan nama field
        'field_names' => array_keys($alamat->getAttributes())
    ]);
});






// routes/web.php
Route::get('/checkout', function (Illuminate\Http\Request $request) {
    try {
        // Validasi
        $validated = $request->validate([
            'kos_id' => 'required|exists:kos,id',
            'kamar_id' => 'required|exists:kamar,id',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'periode' => 'required|in:harian,mingguan,bulanan,3_bulanan,6_bulanan,tahunan',
            'harga_total' => 'required|numeric|min:0'
        ]);

        // Ambil data
        $kos = App\Models\Kos::with('alamatKos')->findOrFail($validated['kos_id']);
        $kamar = App\Models\Kamar::with('hargaSewa')->findOrFail($validated['kamar_id']);

        // Periode yang dipilih
        $periode = $validated['periode'];

        // Ambil harga untuk periode yang dipilih
        $hargaPeriode = $kamar->hargaSewa
            ->where('periode', $periode)
            ->first()->harga ?? 0;

        // HARGA SATUAN = harga untuk periode tersebut
        $hargaSatuan = $hargaPeriode; // Misal: 4.000.000 untuk 3 bulan

        // Durasi awal berdasarkan periode
        $durasiAwal = 1;
        if ($periode === '3_bulanan')
            $durasiAwal = 3;
        if ($periode === '6_bulanan')
            $durasiAwal = 6;
        if ($periode === 'tahunan')
            $durasiAwal = 12;

        // HARGA TOTAL = harga satuan saja (karena sudah termasuk durasi awal)
        $hargaTotal = $hargaSatuan; // 4.000.000

        // Mapping konfigurasi
        $periodeConfig = [
            'harian' => ['kelipatan' => 1, 'satuan' => 'hari', 'label' => 'hari', 'max' => 30],
            'mingguan' => ['kelipatan' => 1, 'satuan' => 'minggu', 'label' => 'minggu', 'max' => 12],
            'bulanan' => ['kelipatan' => 1, 'satuan' => 'bulan', 'label' => 'bulan', 'max' => 12],
            '3_bulanan' => ['kelipatan' => 3, 'satuan' => 'bulan', 'label' => '3 bulan', 'max' => 12],
            '6_bulanan' => ['kelipatan' => 6, 'satuan' => 'bulan', 'label' => '6 bulan', 'max' => 12],
            'tahunan' => ['kelipatan' => 12, 'satuan' => 'bulan', 'label' => 'tahun', 'max' => 12]
        ];

        $config = $periodeConfig[$periode] ?? ['kelipatan' => 1, 'satuan' => 'bulan', 'label' => 'bulan', 'max' => 12];

        // Format tanggal
        $tanggal_mulai = \Carbon\Carbon::parse($validated['tanggal_mulai'])->format('d F Y');
        $periodeHari = [
            'harian' => 1,
            'mingguan' => 7,
            'bulanan' => 30,
            '3_bulanan' => 90,
            '6_bulanan' => 180,
            'tahunan' => 365
        ];
        $tanggal_selesai = \Carbon\Carbon::parse($validated['tanggal_mulai'])
            ->addDays($periodeHari[$validated['periode']] ?? 30)
            ->format('d F Y');

        return view('checkout', [
            'kos' => $kos,
            'kamar' => $kamar,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'periode' => $periode,
            'harga_total' => $hargaTotal, // 4.000.000 (BUKAN 12.000.000)
            'periode_label' => [
                'harian' => 'Harian',
                'mingguan' => 'Mingguan',
                'bulanan' => 'Bulanan',
                '3_bulanan' => '3 Bulanan',
                '6_bulanan' => '6 Bulanan',
                'tahunan' => 'Tahunan'
            ][$periode] ?? $periode,

            // Data untuk JavaScript
            'durasiAwal' => $durasiAwal, // 3
            'kelipatan' => $config['kelipatan'], // 3
            'satuan' => $config['satuan'], // 'bulan'
            'labelSatuan' => $config['label'], // '3 bulan'
            'minDurasi' => $config['kelipatan'], // 3
            'maxDurasi' => $config['max'], // 12
            'hargaSatuan' => $hargaSatuan // 4.000.000
        ]);

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
    }
})->name('checkout');


Route::post('/checkout', function (Illuminate\Http\Request $request) {
    try {
        // 1. VALIDASI
        $validated = $request->validate([
            'kos_id' => 'required|integer|exists:kos,id',
            'kamar_id' => 'required|integer|exists:kamar,id',
            'tanggal_mulai_final' => 'required|date|after_or_equal:today',
            'periode_final' => 'required|in:harian,mingguan,bulanan,3_bulanan,6_bulanan,tahunan',
            'harga_total_final' => 'required|numeric|min:0',
            'durasi_final' => 'required|integer|min:1',
            'catatan_penyewa' => 'nullable|string|max:500',
            'ktp_file' => 'nullable|file|max:2048|mimes:jpg,jpeg,png,pdf'
        ]);

        $kamar = \DB::table('kamar')->find($validated['kamar_id']);
        if (!$kamar) {
            return redirect()->back()
                ->with('error', 'Kamar tidak ditemukan.')
                ->withInput();
        }

        // 2. UPLOAD FILE (jika ada)
        $ktpPath = null;
        if ($request->hasFile('ktp_file')) {
            $file = $request->file('ktp_file');
            $fileName = 'ktp_' . auth()->id() . '_' . time() . '.' . $file->extension();
            $path = $file->storeAs('ktp_documents', $fileName, 'public');
            $ktpPath = $path;
            \Log::info('KTP uploaded:', ['path' => $path]);
        }

        // 3. GENERATE KODE
        $kodeCheckin = 'KOS' . strtoupper(Str::random(3)) . '-' . time();

        // 4. INSERT KE DATABASE (HANYA KOLOM YANG ADA)
        $bookingData = [
            'user_id' => auth()->id() ?? 1,
            'kos_id' => $validated['kos_id'],
            'kamar_id' => $validated['kamar_id'],
            'tanggal_checkin' => $validated['tanggal_mulai_final'],
            'kode_checkin' => $kodeCheckin,
            'durasi_sewa' => $validated['durasi_final'],
            'periode_sewa' => $validated['periode_final'],
            'total_harga' => $validated['harga_total_final'],
            'status' => 'menunggu_konfirmasi',
            'catatan_penyewa' => $validated['catatan_penyewa'] ?? null,
            // 'created_at' => now(), // TIDAK PERLU, sudah default CURRENT_TIMESTAMP
        ];
        $bookingId = \DB::table('booking')->insertGetId($bookingData);

        return redirect()->route('persetujuan', ['booking_id' => $bookingId])
            ->with('success', 'Booking berhasil diajukan! Kode: ' . $kodeCheckin);

    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Validation error:', $e->errors());
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput();

    } catch (\Exception $e) {
        \Log::error('❌ Checkout error:', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString()
        ]);

        return redirect()->back()
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
            ->withInput();
    }
})->name('checkout.store');



// Update route persetujuan
Route::get('/persetujuan/{booking_id}', function ($booking_id) {
    try {
        // PAKAI ELOQUENT (lebih clean)
        $booking = \App\Models\Booking::with(['kos', 'kamar'])
            ->findOrFail($booking_id);

        // Cek kepemilikan
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Format tanggal
        $tanggalCheckin = \Carbon\Carbon::parse($booking->tanggal_checkin)
            ->locale('id')
            ->translatedFormat('j F Y');

        // Hitung tanggal selesai
        $tanggalSelesai = \Carbon\Carbon::parse($booking->tanggal_checkin);

        // ... logika periode sewa sama seperti route kedua ...
        if ($booking->periode_sewa === 'harian') {
            $tanggalSelesai->addDays($booking->durasi_sewa);
        } elseif ($booking->periode_sewa === 'mingguan') {
            $tanggalSelesai->addWeeks($booking->durasi_sewa);
        } elseif ($booking->periode_sewa === 'bulanan') {
            $tanggalSelesai->addMonths($booking->durasi_sewa);
        } elseif ($booking->periode_sewa === '3_bulanan') {
            $tanggalSelesai->addMonths($booking->durasi_sewa * 3);
        } elseif ($booking->periode_sewa === '6_bulanan') {
            $tanggalSelesai->addMonths($booking->durasi_sewa * 6);
        } elseif ($booking->periode_sewa === 'tahunan') {
            $tanggalSelesai->addYears($booking->durasi_sewa);
        }

        $tanggalSelesaiFormatted = $tanggalSelesai->translatedFormat('j F Y');

        return view('persetujuan', [
            'booking' => $booking,
            'kos' => $booking->kos,
            'kamar' => $booking->kamar,
            'fotoKos' => $booking->kos->fotoKos->where('tipe', 'bangunan')->first(),
            'tanggal_checkin' => $tanggalCheckin,
            'tanggal_selesai' => $tanggalSelesaiFormatted,
            'durasi' => $booking->durasi_sewa,
            'periode' => $booking->periode_sewa,
            'total_harga' => $booking->total_harga,
            'kode_checkin' => $booking->kode_checkin,
            'status' => $booking->status,
            'catatan_penyewa' => $booking->catatan_penyewa,
        ]);

    } catch (\Exception $e) {
        \Log::error('Error loading persetujuan:', ['error' => $e->getMessage()]);
        return redirect()->route('home')->with('error', 'Gagal memuat data booking');
    }
})->name('persetujuan');


Route::post('/booking/{id}/accept', function ($id) {
    try {
        $booking = \DB::table('booking')->find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking tidak ditemukan'], 404);
        }

        \DB::beginTransaction();

        // 1. Update status booking menjadi 'diterima'
        \DB::table('booking')
            ->where('id', $id)
            ->update(['status' => 'menunggu_pembayaran']);

        // 2. Update status kamar menjadi 'terisi'
        \DB::table('kamar')
            ->where('id', $booking->kamar_id)
            ->update(['status' => 'terisi']);

        \DB::commit();

        \Log::info('✅ Booking accepted:', [
            'booking_id' => $id,
            'kamar_id' => $booking->kamar_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking diterima dan status kamar diperbarui'
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('❌ Error accepting booking:', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('booking.accept');


Route::post('/booking/{id}/reject', function ($id) {
    try {
        $booking = \DB::table('booking')->find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking tidak ditemukan'], 404);
        }

        // Update status booking menjadi 'ditolak'
        \DB::table('booking')
            ->where('id', $id)
            ->update(['status' => 'ditolak']);

        // Status kamar TETAP tidak berubah (masih tersedia)

        \Log::info('✅ Booking rejected:', ['booking_id' => $id]);

        return response()->json([
            'success' => true,
            'message' => 'Booking ditolak'
        ]);

    } catch (\Exception $e) {
        \Log::error('❌ Error rejecting booking:', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('booking.reject');


Route::post('/booking/{id}/cancel', function ($id) {
    try {
        $booking = \DB::table('booking')->find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking tidak ditemukan'], 404);
        }

        \DB::beginTransaction();

        // 1. Update status booking menjadi 'dibatalkan'
        \DB::table('booking')
            ->where('id', $id)
            ->update(['status' => 'dibatalkan']);

        // 2. Update status kamar menjadi 'tersedia' kembali
        \DB::table('kamar')
            ->where('id', $booking->kamar_id)
            ->update(['status' => 'tersedia']);

        \DB::commit();

        \Log::info('✅ Booking canceled:', [
            'booking_id' => $id,
            'kamar_id' => $booking->kamar_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking dibatalkan dan kamar tersedia kembali'
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('❌ Error canceling booking:', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('booking.cancel');


Route::post('/booking/{id}/complete', function ($id) {
    try {
        $booking = \DB::table('booking')->find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking tidak ditemukan'], 404);
        }

        \DB::beginTransaction();

        // 1. Update status booking menjadi 'selesai'
        \DB::table('booking')
            ->where('id', $id)
            ->update(['status' => 'selesai']);

        // 2. Update status kamar menjadi 'tersedia' untuk disewa lagi
        \DB::table('kamar')
            ->where('id', $booking->kamar_id)
            ->update(['status' => 'tersedia']);

        \DB::commit();

        \Log::info('✅ Booking completed:', [
            'booking_id' => $id,
            'kamar_id' => $booking->kamar_id
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking selesai dan kamar tersedia kembali'
        ]);

    } catch (\Exception $e) {
        \DB::rollBack();
        \Log::error('❌ Error completing booking:', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
})->name('booking.complete');

// routes/web.php
Route::post('/api/midtrans-callback', function (Illuminate\Http\Request $request) {
    \Log::info('🎯 Midtrans Callback Received (web.php):', $request->all());

    $orderId = $request->order_id;
    $transactionStatus = $request->transaction_status;
    $grossAmount = $request->gross_amount;

    // Parse booking_id dari order_id
    $bookingId = null;
    if (preg_match('/^BOOK-(\d+)-/', $orderId, $matches)) {
        $bookingId = $matches[1];
        \Log::info('📦 Parsed booking_id: ' . $bookingId);
    }

    if (!$bookingId) {
        \Log::error('❌ Invalid order_id format');
        return response()->json(['status' => 'error', 'message' => 'Invalid order_id'], 400);
    }

    $booking = \App\Models\Booking::find($bookingId);

    if (!$booking) {
        \Log::error('❌ Booking not found');
        return response()->json(['status' => 'error', 'message' => 'Booking not found'], 404);
    }

    \Log::info('✅ Booking found:', ['booking_id' => $booking->id]);

    // Handle transaction status
    if (in_array($transactionStatus, ['capture', 'settlement'])) {
        \DB::beginTransaction();
        try {
            // 1. Simpan pembayaran
            \App\Models\Pembayaran::create([
                'booking_id' => $booking->id,
                'tanggal_bayar' => now(),
                'total_bayar' => $grossAmount,
                'metode_bayar' => $request->payment_type ?? 'midtrans',
                'status' => 'success',
            ]);

            // 2. Update booking status
            $booking->update(['status' => 'selesai']);

            // 3. Update kamar status
            if ($booking->kamar_id) {
                \App\Models\Kamar::where('id', $booking->kamar_id)
                    ->update(['status' => 'terisi']);
            }

            \DB::commit();
            \Log::info('✅ Payment success for booking: ' . $booking->id);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('❌ Error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    return response()->json(['status' => 'success']);
});



Auth::routes();

Route::get('/test', function () {
    return view('test');
});

Route::get('/', [KosController::class, 'index'])->name('home');

Route::get('/search', [KosController::class, 'search'])->name('kos.search');

Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
Route::get('/api/ulasan/{id}', [UlasanController::class, 'show']);
Route::put('/ulasan/{id}', [UlasanController::class, 'update']);

Route::get('/detail_kos/{id}', [KosController::class, 'show'])->name('detail.kos');

Route::get('/profile', function () {
    return view('user_profile');
})->name('user.profile');

Route::get('/my_booking', function () {
    if (!auth()->check()) {
        return redirect('/');
    }

    // Ambil SEMUA booking user
    $allBookings = \App\Models\Booking::with(['kos', 'kamar', 'kos.fotoKos'])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

    $userUlasan = \App\Models\Ulasan::where('user_id', auth()->id())->get();

    // Filter berdasarkan status untuk masing-masing tab
    $menungguBookings = $allBookings->where('status', 'menunggu_konfirmasi');
    $pembayaranBookings = $allBookings->whereIn('status', ['menunggu_pembayaran']);
    $checkinBookings = $allBookings->where('status', 'selesai');
    $cancelBookings = $allBookings->whereIn('status', ['ditolak', 'dibatalkan']);

    return view('user_profile_booking', [
        'allBookings' => $allBookings,
        'menungguBookings' => $menungguBookings,
        'pembayaranBookings' => $pembayaranBookings,
        'checkinBookings' => $checkinBookings,
        'cancelBookings' => $cancelBookings,
        'userUlasan' => $userUlasan,
        'bookings' => $allBookings,
        'kos' => $allBookings->first()->kos ?? null,
        'tanggal_checkin' => $allBookings->first()->tanggal_checkin ?? null,
    ]);
})->name('my.booking');

// Buat route untuk update status
Route::post('/update-booking-status', function (Request $request) {
    $bookingId = $request->booking_id;
    $status = $request->status; // 'selesai'

    $booking = \App\Models\Booking::find($bookingId);
    if ($booking) {
        $booking->status = $status;
        $booking->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated'
        ]);
    }

    return response()->json(['success' => false], 404);
})->middleware('auth');

Route::post('/cancel-booking/{booking_id}', function ($booking_id, Request $request) {
    $booking = \App\Models\Booking::findOrFail($booking_id);

    // Cek ownership
    if ($booking->user_id !== auth()->id()) {
        return response()->json(['success' => false, 'error' => 'Unauthorized'], 403);
    }

    // Cek status yang boleh dicancel
    $requestStatus = $request->input('status', $booking->status);
    $allowedStatuses = ['menunggu_konfirmasi', 'menunggu_pembayaran'];

    if (!in_array($booking->status, $allowedStatuses)) {
        return response()->json([
            'success' => false,
            'error' => "Booking dengan status '{$booking->status}' tidak bisa dibatalkan"
        ], 400);
    }

    $statusSebelumnya = $booking->status;
    if ($statusSebelumnya === 'menunggu_pembayaran') {
        // a. Update kamar jadi 'tersedia'
        \DB::table('kamar')
            ->where('id', $booking->kamar_id)
            ->update(['status' => 'tersedia']);

        // b. Hitung ulang kamar tersedia untuk kos ini
        $jumlahTersedia = \DB::table('kamar')
            ->where('kos_id', $booking->kos_id)
            ->where('status', 'tersedia')
            ->count();

        // c. Update tabel kos
        \DB::table('kos')
            ->where('id', $booking->kos_id)
            ->update(['kamar_tersedia' => $jumlahTersedia]);
    }

    // Update status
    $booking->status = 'dibatalkan';
    $booking->save();

    return response()->json([
        'success' => true,
        'message' => 'Booking berhasil dibatalkan',
        'booking_id' => $booking->id
    ]);
})->middleware('auth');

Route::get('/pembayaran', function () {
    return view('pembayaran');
});

Route::get('/check_in', function () {
    return view('check_in');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});






// Reset Password Routes
Route::post('/password/generate-reset-link', [ResetPasswordController::class, 'generateResetLink'])
    ->name('password.generate-link');

// Route dengan token (WAJIB ada token)
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset.form');

// Route untuk handle form submission
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
    ->name('password.reset.submit');

// Route untuk halaman sukses - TAMBAHKAN ->name()
Route::get('/reset-success', function () {
    return view('auth.passwords.reset-success');
})->name('password.reset.success');

// Route::get('/reset-password', function () {
//     return view('auth.passwords.reset-password');
// });