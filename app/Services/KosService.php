<?php

namespace App\Services;

use App\Models\Kos;
use App\Models\Kamar;
use App\Models\Fasilitas;
use App\Models\Peraturan;
use App\Models\AlamatKos;
use App\Models\HargaSewa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KosService
{
    // Mapping periode harga yang benar
    private const PRICE_MAPPING = [
        'monthly' => 'bulanan',
        'daily' => 'harian',
        'weekly' => 'mingguan',
        'three_monthly' => '3_bulanan',
        'six_monthly' => '6_bulanan',
        'yearly' => 'tahunan',
    ];

    /**
     * Create new kos dengan semua relasinya
     */
    public function createKos(array $data, int $userId): Kos
    {
        Log::info('Creating kos', ['user_id' => $userId]);

        return DB::transaction(function () use ($data, $userId) {
            // 1. CREATE KOS UTAMA
            $kos = $this->createKosModel($data, $userId);
            Log::info('Kos created', ['kos_id' => $kos->id]);

            // 2. CREATE ALAMAT
            $this->createAlamat($kos, $data);
            Log::info('Alamat created for kos', ['kos_id' => $kos->id]);

            // 3. CREATE KAMAR DENGAN HARGA
            $this->createKamars($kos, $data);
            Log::info('Kamars created', ['kos_id' => $kos->id, 'count' => count($data['room_details'] ?? [])]);

            // 4. SYNC FASILITAS
            $this->syncFasilitas($kos, $data['fasilitas'] ?? []);
            Log::info('Fasilitas synced', ['kos_id' => $kos->id, 'count' => count($data['fasilitas'] ?? [])]);

            // 5. SYNC PERATURAN
            $this->syncPeraturan($kos, $data['rules'] ?? []);
            Log::info('Peraturan synced', ['kos_id' => $kos->id, 'count' => count($data['rules'] ?? [])]);

            $this->saveBase64Photos($kos, $data);

            return $kos;
        });
    }

    /**
     * Update existing kos berdasarkan database ID
     */
    public function updateKos(int $kosId, array $data, int $userId): Kos
    {
        Log::info('Updating kos', ['kos_id' => $kosId, 'user_id' => $userId]);

        return DB::transaction(function () use ($kosId, $data, $userId) {
            // 1. FIND KOS by database ID
            $kos = Kos::where('id', $kosId)
                ->where('user_id', $userId)
                ->firstOrFail();
            Log::info('Kos found for update', ['kos_id' => $kos->id]);

            // 2. UPDATE KOS UTAMA
            $this->updateKosModel($kos, $data);

            // 3. UPDATE ALAMAT
            $this->updateAlamat($kos, $data);

            // 4. UPDATE KAMAR (delete lama, create baru)
            $kos->kamar()->delete();
            $this->createKamars($kos, $data);
            Log::info('Kamars updated', ['kos_id' => $kos->id]);

            // 5. SYNC FASILITAS & PERATURAN
            $this->syncFasilitas($kos, $data['fasilitas'] ?? []);
            $this->syncPeraturan($kos, $data['rules'] ?? []);

            $this->saveBase64Photos($kos, $data);

            return $kos->refresh(); // Reload dengan relasi terbaru
        });
    }

    /**
     * Create Kos model (TANPA local_id)
     */
    private function createKosModel(array $data, int $userId): Kos
    {
        return Kos::create([
            'user_id' => $userId,
            'nama_kos' => $data['nama_kos'],
            'tipe_kos' => $data['tipe_kos'],
            'deskripsi' => $data['deskripsi'] ?? '',
            'status' => 'active',
            'total_kamar' => count($data['room_details'] ?? []),
            'kamar_tersedia' => $this->countAvailableRooms($data['room_details'] ?? []),
            // Hapus local_id dari sini
        ]);
    }

    /**
     * Update Kos model
     */
    private function updateKosModel(Kos $kos, array $data): void
    {
        $kos->update([
            'nama_kos' => $data['nama_kos'] ?? $kos->nama_kos,
            'tipe_kos' => $data['tipe_kos'] ?? $kos->tipe_kos,
            'deskripsi' => $data['deskripsi'] ?? $kos->deskripsi,
            'total_kamar' => count($data['room_details'] ?? $kos->kamar),
            'kamar_tersedia' => $this->countAvailableRooms($data['room_details'] ?? []),
        ]);
    }

    /**
     * Create alamat untuk kos
     */
    private function createAlamat(Kos $kos, array $data): void
    {
        $kos->alamatKos()->create([
            'alamat' => $data['alamat'] ?? '',
            'provinsi' => $data['provinsi'] ?? '',
            'kabupaten' => $data['kabupaten'] ?? '',
            'kecamatan' => $data['kecamatan'] ?? '',
            'catatan_alamat' => $data['catatan_alamat'] ?? '',
        ]);
    }

    /**
     * Update alamat kos
     */
    private function updateAlamat(Kos $kos, array $data): void
    {
        if ($kos->alamatKos) {
            $kos->alamatKos->update([
                'alamat' => $data['alamat'] ?? $kos->alamatKos->alamat,
                'provinsi' => $data['provinsi'] ?? $kos->alamatKos->provinsi,
                'kabupaten' => $data['kabupaten'] ?? $kos->alamatKos->kabupaten,
                'kecamatan' => $data['kecamatan'] ?? $kos->alamatKos->kecamatan,
                'catatan_alamat' => $data['catatan_alamat'] ?? $kos->alamatKos->catatan_alamat,
            ]);
        } else {
            $this->createAlamat($kos, $data);
        }
    }

    /**
     * Create kamar dengan harga sewa
     */
    private function createKamars(Kos $kos, array $data): void
    {
        $roomDetails = $data['room_details'] ?? [];
        $priceData = $data['price'] ?? [];

        foreach ($roomDetails as $index => $room) {
            // Create kamar
            $kamar = $kos->kamar()->create([
                'nama_kamar' => $room['nomor'] ?? $room['no_kamar'] ?? 'Kamar ' . ($index + 1),
                'ukuran_kamar' => $data['size']['type'] ?? '3 x 4',
                'lantai' => $room['lantai'] ?? 1,
                'status' => ($room['terisi'] ?? false) ? 'terisi' : 'tersedia',
            ]);

            // Create harga sewa (hanya yang > 0)
            $this->createHargaSewa($kamar, $priceData);
        }
    }

    /**
     * Create harga sewa untuk kamar
     * Hanya simpan harga yang > 0
     */
    private function createHargaSewa(Kamar $kamar, array $priceData): void
    {
        \Log::info('Price data received:', $priceData);

        $hargaRecords = [];

        foreach (self::PRICE_MAPPING as $dataKey => $periode) {
            $harga = $priceData[$dataKey] ?? 0;

            // Convert string ke integer jika perlu
            if (is_string($harga)) {
                $harga = (int) preg_replace('/[^0-9]/', '', $harga);
            }

            if ($harga > 0) {
                $dendaPerHari = null;
                $batasHariDenda = null;

                if (isset($priceData['set_fine']) && $priceData['set_fine']) {
                    $dendaPerHari = $priceData['fine_amount'] ?? 0;
                    $batasHariDenda = $priceData['fine_limit'] ?? null;

                    // Convert ke integer jika string
                    if (is_string($dendaPerHari)) {
                        $dendaPerHari = (int) preg_replace('/[^0-9]/', '', $dendaPerHari);
                    }
                    if (is_string($batasHariDenda)) {
                        $batasHariDenda = (int) $batasHariDenda;
                    }

                    // Jika denda 0 atau negative, set null
                    if ($dendaPerHari <= 0) {
                        $dendaPerHari = null;
                    }
                    if ($batasHariDenda <= 0) {
                        $batasHariDenda = null;
                    }
                }

                $hargaRecords[] = [
                    'kamar_id' => $kamar->id,
                    'periode' => $periode,
                    'harga' => $harga,
                    'denda_per_hari' => $dendaPerHari,
                    'batas_hari_denda' => $batasHariDenda,
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                \Log::info("Harga record prepared:", [
                    'periode' => $periode,
                    'harga' => $harga,
                    'denda_per_hari' => $dendaPerHari,
                    'batas_hari_denda' => $batasHariDenda
                ]);
            }
        }

        if (!empty($hargaRecords)) {
            DB::table('harga_sewa')->insert($hargaRecords);
            \Log::info('=== HARGA SEWA SAVED ===', ['count' => count($hargaRecords)]);
        } else {
            \Log::warning('No harga records to save');
        }
    }

    /**
     * Sync fasilitas kos
     */
    private function syncFasilitas(Kos $kos, array $fasilitas): void
    {
        $fasilitasIds = [];

        foreach ($fasilitas as $nama) {
            $nama = trim($nama);
            if (empty($nama))
                continue;

            $fasilitasId = Fasilitas::firstOrCreate([
                'nama_fasilitas' => $nama
            ])->id;

            $fasilitasIds[] = $fasilitasId;
        }

        $kos->fasilitas()->sync($fasilitasIds);
    }

    /**
     * Sync peraturan kos
     */
    private function syncPeraturan(Kos $kos, array $rules): void
    {
        $peraturanIds = [];

        foreach ($rules as $nama) {
            $nama = trim($nama);
            if (empty($nama))
                continue;

            $peraturanId = Peraturan::firstOrCreate([
                'nama_peraturan' => $nama
            ])->id;

            $peraturanIds[] = $peraturanId;
        }

        $kos->peraturan()->sync($peraturanIds);
    }

    /**
     * Simpan foto Base64 ke database
     */
    private function saveBase64Photos(Kos $kos, array $data): void
    {
        \Log::info('=== SAVE BASE64 PHOTOS START ===');
        \Log::info('Kos ID:', ['id' => $kos->id]);
        \Log::info('Image data exists:', ['exists' => isset($data['images'])]);

        // Hapus foto lama jika update
        if ($kos->id && isset($data['images'])) {
            \App\Models\FotoKos::where('kos_id', $kos->id)->delete();
            \Log::info('Deleted old photos');
        }

        // Cek apakah ada data images
        if (empty($data['images']) || !is_array($data['images'])) {
            \Log::warning('No images data found in saveBase64Photos');
            return;
        }

        $savedCount = 0;

        foreach ($data['images'] as $type => $imageArray) {
            \Log::info('Processing image type:', ['type' => $type]);

            if (!is_array($imageArray)) {
                \Log::warning('Image data is not array, converting...');
                $imageArray = [$imageArray];
            }

            foreach ($imageArray as $index => $base64String) {
                \Log::info('Image data:', [
                    'index' => $index,
                    'length' => strlen($base64String),
                    'first_50_chars' => substr($base64String, 0, 50)
                ]);

                try {
                    // Validasi Base64 string
                    if (empty($base64String)) {
                        \Log::warning('Empty base64 string');
                        continue;
                    }

                    // Simpan ke database menggunakan model
                    \App\Models\FotoKos::create([
                        'kos_id' => $kos->id,
                        'tipe' => $this->mapImageType($type),
                        'path_foto' => $base64String, // Mutator akan handle stripping header
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $savedCount++;
                    \Log::info('✅ Base64 image saved', [
                        'type' => $type,
                        'index' => $index
                    ]);

                } catch (\Exception $e) {
                    \Log::error('❌ Error saving base64 image:', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }
        }

        \Log::info('=== SAVE BASE64 PHOTOS END ===', ['total_saved' => $savedCount]);
    }

    /**
     * Map frontend image type to database type
     */

    private function mapImageType(string $frontendType): string
    {
        $mapping = [
            'depan' => 'bangunan',
            'bangunan' => 'bangunan',
            'kamar' => 'kamar',
            'dalam' => 'kamar',
            'luar' => 'bangunan'
        ];

        return $mapping[$frontendType] ?? 'bangunan';
    }

    /**
     * Count available rooms from room details
     */
    private function countAvailableRooms(array $roomDetails): int
    {
        $available = 0;
        foreach ($roomDetails as $room) {
            if (!($room['terisi'] ?? false)) {
                $available++;
            }
        }
        return $available;
    }

    /**
     * Get kos list untuk user tertentu
     */

    public function getKosList(int $userId)
    {
        $kosList = Kos::with([
            'alamatKos',
            'kamar.hargaSewa',
            'fasilitas',
            'peraturan',
            'fotoKos'
        ])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Log untuk debugging
        \Log::info('Kos list with photos:', [
            'count' => $kosList->count(),
            'first_kos_photos' => $kosList->first()->fotoKos->count() ?? 0
        ]);

        return $kosList;
    }

    /**
     * Get single kos dengan semua relasi berdasarkan database ID
     */
    public function getKosWithRelations(int $kosId, int $userId)
    {
        $kosId = (int) $kosId;
        return Kos::with([
            'alamatKos',
            'kamar.hargaSewa',
            'fasilitas',
            'peraturan'
        ])
            ->where('id', $kosId) // Ganti dari local_id ke id
            ->where('user_id', $userId)
            ->firstOrFail();
    }

    /**
     * Delete kos beserta semua relasinya berdasarkan database ID
     */
    public function deleteKos(int $kosId, int $userId): bool
    {
        $kos = Kos::where('id', $kosId) // Ganti dari local_id ke id
            ->where('user_id', $userId)
            ->firstOrFail();

        return DB::transaction(function () use ($kos) {
            // Hapus semua relasi
            $kos->kamar()->delete();
            $kos->alamatKos()->delete();
            $kos->fasilitas()->detach();
            $kos->peraturan()->detach();

            // Hapus kos utama
            return $kos->delete();
        });
    }

    /**
     * Cek apakah kos ada (untuk validation)
     */
    public function kosExists(int $kosId, int $userId): bool
    {
        return Kos::where('id', $kosId)
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get simple kos data untuk dropdown/list
     */
    public function getSimpleKosList(int $userId)
    {
        return Kos::where('user_id', $userId)
            ->select('id', 'nama_kos', 'status')
            ->orderBy('nama_kos')
            ->get();
    }
}