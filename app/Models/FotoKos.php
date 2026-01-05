<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoKos extends Model
{
    use HasFactory;

    protected $table = 'foto_kos';

    protected $fillable = [
        'kos_id',
        'tipe',
        'path_foto',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'full_base64_url'
    ];
    

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }

    public function getFullBase64UrlAttribute()
    {
        if (empty($this->path_foto)) {
            return null;
        }

        // Cek apakah path_foto sudah mengandung header
        if (str_contains($this->path_foto, 'base64,')) {
            return $this->path_foto;
        }

        // Tambahkan header jika belum ada
        // Untuk sekarang asumsi semua JPEG, bisa diperbaiki nanti
        return 'data:image/jpeg;base64,' . $this->path_foto;
    }

    /**
     * Accessor untuk mendapatkan URL file jika dikonversi ke file
     * (Untuk implementasi berikutnya)
     */
    public function getImageUrlAttribute()
    {
        // Jika ingin menyimpan sebagai file di storage:
        // return asset('storage/kos-images/' . $this->id . '.jpg');
        return null;
    }

    /**
     * Mutator untuk menyimpan hanya pure Base64 (tanpa header)
     */
    public function setPathFotoAttribute($value)
    {
        // Jika datang dengan header "data:image/...;base64,"
        if (str_contains($value, 'base64,')) {
            $parts = explode(',', $value, 2);
            $this->attributes['path_foto'] = $parts[1] ?? $value;
        } else {
            $this->attributes['path_foto'] = $value;
        }
    }
}

