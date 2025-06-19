<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini
use Illuminate\Database\Eloquent\Casts\Attribute;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'lost_time',
        'estimated_location',
        'image',
        'status',
    ];

    protected $casts = [
        'lost_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tambahkan relasi ini untuk menghubungkan ke model aduan
     */
    public function reports(): HasMany
    {
        return $this->hasMany(AnnouncementReport::class);
    }
    
    // Tambahkan scopeFilter untuk fungsionalitas pencarian dan filter
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('estimated_location', 'like', '%' . $search . '%');
            });
        });

        $query->when($filters['lost_date'] ?? false, function ($query, $date) {
            return $query->whereDate('lost_time', $date);
        });
    }

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