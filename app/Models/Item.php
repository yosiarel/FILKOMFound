<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * DIUBAH: Disesuaikan dengan kolom dari form dan controller.
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'description', 
        'location',
        'found_date',
        'image', 
        'status',
        'user_id'
    ];

    /**
     * Mendefinisikan bahwa sebuah Item dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all of the reports for the Item.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
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
}