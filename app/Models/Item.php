<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute; // Pastikan ini ada di atas

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Pastikan ini cocok dengan nama kolom di file migrasi Anda.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'item_name', // <-- PERBAIKAN 1: Harus 'item_name', bukan 'name'
        'description', 
        'location',
        'found_date',
        'image', 
        'status',
    ];

    /**
     * Mendefinisikan bahwa sebuah Item dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the claims for the Item.
     */
    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    /**
     * Get the verification associated with the Item.
     */
    public function verification(): HasOne
    {
        return $this->hasOne(Verification::class);
    }

    /**
     * PERBAIKAN 2: Accessor untuk mengubah nilai 'status' menjadi teks yang mudah dibaca.
     * Logika accessor harus berada di dalam sebuah method.
     * Method ini akan membuat properti virtual baru bernama 'formatted_status'.
     */
    protected function formattedStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                'found' => 'Tersedia',
                'claimed' => 'Sedang Diklaim',
                'returned' => 'Sudah Dikembalikan',
                default => 'Tidak Diketahui',
            },
        );
    }
}