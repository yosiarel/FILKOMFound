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
        'verified_at', 
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
    public function scopeFilter($query, array $filters)
    {
    // Filter berdasarkan kata kunci pencarian (search)
    $query->when($filters['search'] ?? false, function ($query, $search) {
        return $query->where(function ($query) use ($search) {
            $query->where('item_name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
        });
    });

    // Filter berdasarkan status
    $query->when($filters['status'] ?? false, function ($query, $status) {
        return $query->where('status', $status);
    });

     $query->when($filters['found_date'] ?? false, function ($query, $date) {
        return $query->whereDate('found_date', $date); // Membandingkan tanggal saja
    });
    
    // Anda bisa menambahkan filter lain di sini, misalnya berdasarkan tanggal (posted_at)
    }
    protected function adminFormattedStatus(): Attribute
    {
    return Attribute::make(
        get: function () {
            // Prioritas pertama: jika belum diverifikasi
            if (!$this->verified_at) {
                return 'Belum Diverifikasi';
            }
            // Jika sudah diverifikasi, gunakan status yang ada
            return match ($this->status) {
                'found' => 'Belum Dikembalikan',
                'claimed' => 'Sedang Diajukan',
                'returned' => 'Sudah Dikembalikan',
                default => 'Tidak Diketahui',
            };
        },
    );
    }
}