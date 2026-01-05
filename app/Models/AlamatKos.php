<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlamatKos extends Model
{
    use HasFactory;

    protected $table = 'alamat_kos';

    protected $fillable = [
        'kos_id',
        'alamat',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'catatan_alamat',
        'lat',
        'lon',
    ];

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }
}

