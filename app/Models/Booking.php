<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';

    public $timestamps = true; // kalau di migration pakai timestamps()

    protected $fillable = [
        'user_id',
        'kos_id',
        'kamar_id',
        'tanggal_checkin',
        'kode_checkin',
        'durasi_sewa',
        'periode_sewa',
        'total_harga',
        'status',
        'catatan_penyewa',
    ];

    public function penyewa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'booking_id');
    }

    public function kontrak()
    {
        return $this->hasOne(Kontrak::class, 'booking_id');
    }
}

