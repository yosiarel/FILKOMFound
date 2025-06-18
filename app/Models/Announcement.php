<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute; // <-- Pastikan ini ada

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'lost_time',
        'estimated_location',
        'image',
        'status', // <-- PERBAIKAN 1: Tambahkan 'status' di sini
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'lost_time' => 'datetime',
    ];

    /**
     * Relasi ke model User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * REKOMENDASI: Accessor untuk mengubah nilai 'status' menjadi teks.
     * Ini akan membuat properti virtual baru bernama 'formatted_status'.
     */
    protected function formattedStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                'found' => 'Sudah Ditemukan',
                'lost'  => 'Belum Ditemukan',
                default => 'Tidak Diketahui',
            },
        );
    }
}