<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';

    public $timestamps = false;

    protected $fillable = [
        'nama_fasilitas',
        'kategori',
    ];

    public function kos()
    {
        return $this->belongsToMany(Kos::class, 'kos_fasilitas', 'fasilitas_id', 'kos_id');
    }
}

