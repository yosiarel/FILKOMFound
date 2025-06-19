<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnnouncementReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'user_id',
        'reason',
        'status',
    ];

    /**
     * Relasi ke pengumuman yang dilaporkan.
     */
    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }

    /**
     * Relasi ke pengguna yang membuat laporan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}