<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peraturan extends Model
{
    use HasFactory;

    protected $table = 'peraturan';

    public $timestamps = false;

    protected $fillable = [
        'nama_peraturan',
    ];

    public function kos()
    {
        return $this->belongsToMany(Kos::class, 'kos_peraturan', 'peraturan_id', 'kos_id');
    }
}

