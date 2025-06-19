<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute; // Pastikan ini di-import

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_name',
        'description', 
        'location',
        'found_date',
        'image', 
        'status',
        'verified_at', 
    ];

    protected $casts = [
        'found_date' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }

    public function verification(): HasOne
    {
        return $this->hasOne(Verification::class);
    }

    // --- PINDAHKAN SEMUA LOGIKA INI KE DALAM MODEL ---

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('item_name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['status'] ?? false, function ($query, $status) {
            return $query->where('status', $status);
        });

        $query->when($filters['found_date'] ?? false, function ($query, $date) {
            return $query->whereDate('found_date', $date);
        });
    }
    
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