<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class KosFasilitas extends Pivot
{
    protected $table = 'kos_fasilitas';
    public $timestamps = false;
    protected $fillable = ['kos_id', 'fasilitas_id'];
    
    // Tidak perlu relasi karena sudah extend Pivot
    // Laravel akan handle otomatis
}