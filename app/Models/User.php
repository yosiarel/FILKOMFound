<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // Pastikan 'nim' dan 'profile_picture' (jika ada) ada di fillable jika ingin diisi secara massal
    protected $fillable = ['name', 'email', 'password', 'nim', 'role', 'profile_picture']; // <--- Tambahkan 'profile_picture' jika Anda akan menggunakannya

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Mendefinisikan bahwa seorang User bisa memiliki banyak Item yang berstatus 'found'.
     * Ini akan digunakan untuk "Riwayat Temuan".
     */
    public function foundItems(): HasMany // <--- Mengganti/memperjelas fungsi 'items'
    {
        return $this->hasMany(Item::class)->where('status', 'found');
    }
    
    /**
     * Mendefinisikan bahwa seorang User bisa memiliki banyak Item yang berstatus 'lost'.
     * Ini akan digunakan untuk "Riwayat Laporan Kehilangan".
     */
    public function lostItems(): HasMany // <--- Relasi baru untuk item hilang
    {
        return $this->hasMany(Item::class)->where('status', 'lost');
    }

    // <-- Sesuaikan relasi announcements() ini: -->
    /**
     * Mendefinisikan bahwa seorang User bisa memiliki banyak Announcement (jika user bisa membuat pengumuman umum).
     * Jika ini hanya untuk pengumuman sistem yang dibuat admin, relasi ini mungkin tidak diperlukan di sini.
     */
    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class); // Jika user bisa membuat pengumuman
    }

    /**
     * Get all of the reports for the User.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Get all of the claims for the User.
     */
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }
}