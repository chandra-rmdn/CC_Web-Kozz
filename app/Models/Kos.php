<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    use HasFactory;

    protected $table = 'kos';

    protected $fillable = [
        'user_id',
        'nama_kos',
        'tipe_kos',
        'deskripsi',
        'mean_rating',
        'status',
        'total_kamar',
        'kamar_tersedia',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function alamatKos()
    {
        return $this->hasOne(AlamatKos::class, 'kos_id');
    }

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'kos_id');
    }

    public function hargaSewa()
    {
        return $this->hasMany(HargaSewa::class, 'kos_id');
    }

    public function fotoKos()
    {
        return $this->hasMany(FotoKos::class, 'kos_id');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'kos_fasilitas', 'kos_id', 'fasilitas_id')
            ->using(KosFasilitas::class);
    }

    public function peraturan()
    {
        return $this->belongsToMany(Peraturan::class, 'kos_peraturan', 'kos_id', 'peraturan_id')
            ->using(KosPeraturan::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'kos_id');
    }

    public function favoritOleh()
    {
        return $this->belongsToMany(User::class, 'favorit', 'kos_id', 'user_id');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'kos_id');
    }
}

