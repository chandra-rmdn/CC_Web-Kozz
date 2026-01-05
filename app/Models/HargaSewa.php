<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaSewa extends Model
{
    use HasFactory;

    protected $table = 'harga_sewa';

    protected $fillable = [
        'kamar_id',
        'periode',
        'harga',
        'denda_per_hari',
        'batas_hari_denda',
    ];

    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }
}
