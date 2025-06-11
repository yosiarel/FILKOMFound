<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Verification extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'status', 'verified_by', 'notes'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
