<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';

    protected $fillable = [
        'kos_id',
        'nama_kamar',
        'lantai',
        'ukuran_kamar',
        'status',
    ];

    public function hargaSewa(): HasMany
    {
        return $this->hasMany(HargaSewa::class, 'kamar_id');
    }

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'kamar_id');
    }
}

