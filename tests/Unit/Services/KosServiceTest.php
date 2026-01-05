<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\KosService;
use App\Models\User;
use App\Models\Kos;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KosServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $kosService;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kosService = app(KosService::class);
        $this->user = User::factory()->create();
    }

    public function test_create_kos()
    {
        $data = [
            'local_id' => 'test123',
            'nama_kos' => 'Kos Test',
            'tipe_kos' => 'putra',
            'deskripsi' => 'Test description',
            'alamat' => 'Jl. Test',
            'provinsi' => 'Jawa Barat',
            'kabupaten' => 'Bandung',
            'kecamatan' => 'Coblong',
            'catatan_alamat' => '',
            'size' => ['type' => '3x4'],
            'room_details' => [
                ['nomor' => 'A1', 'lantai' => 1, 'terisi' => false],
                ['nomor' => 'A2', 'lantai' => 1, 'terisi' => false],
            ],
            'price' => [
                'monthly' => 500000,
                'daily' => 0,
                'weekly' => 0,
            ],
            'fasilitas' => ['Wifi', 'AC'],
            'rules' => ['Akses 24 jam', 'Dilarang merokok'],
        ];

        $kos = $this->kosService->createKos($data, $this->user->id);

        // ✅ RELOAD DENGAN RELASI
        $kos = Kos::with(['kamar', 'fasilitas'])->find($kos->id);

        $this->assertNotNull($kos);
        $this->assertEquals('Kos Test', $kos->nama_kos);
        $this->assertEquals(2, $kos->total_kamar);
        $this->assertEquals(2, $kos->kamar->count());
        $this->assertEquals(2, $kos->fasilitas->count());
    }

}