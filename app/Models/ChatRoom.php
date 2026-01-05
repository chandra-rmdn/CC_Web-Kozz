<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $table = 'chat_room';

    protected $fillable = [
        'kos_id',
        'user_id', // penyewa
    ];

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'kos_id');
    }

    public function pemilikKos()
    {
        return $this->kos->owner; // Mengakses relasi owner dari kos
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // penyewa
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_room_id');
    }
}

