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

    protected $fillable = ['name', 'email', 'password', 'nim', 'role', 'profile_picture'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * ===============================================
     * --- PASTIKAN FUNGSI INI ADA DI DALAM FILE ANDA ---
     * ===============================================
     * Relasi untuk semua item yang dilaporkan oleh user.
     */
    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
}