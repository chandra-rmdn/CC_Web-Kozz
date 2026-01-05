<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'ktp_path',
        'remember_token',
        'no_hp',
        'jenis_kelamin',
        'foto_profil',
        'role',
    ];

    public function getHasKtpAttribute()
    {
        return !empty($this->ktp_path);
    }

    // Optional: URL ke file KTP
    public function getKtpUrlAttribute()
    {
        return $this->ktp_path ? asset('storage/' . $this->ktp_path) : null;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function kos()
    {
        return $this->hasMany(Kos::class, 'user_id'); // kos milik pemilik
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id'); // booking sebagai penyewa
    }

    public function favoritKos()
    {
        return $this->belongsToMany(Kos::class, 'favorit', 'user_id', 'kos_id');
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class, 'user_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
