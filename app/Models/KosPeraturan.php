<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class KosPeraturan extends Pivot
{
    protected $table = 'kos_peraturan';
    public $timestamps = false;
    protected $fillable = ['kos_id', 'peraturan_id'];
}