<?php

namespace App\Policies;

use App\Models\Item;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ItemPolicy
{
    /**
     * Menentukan apakah pengguna dapat memperbarui model.
     * Logika ini akan dipanggil oleh $this->authorize('update', $item);
     */
    public function update(User $user, Item $item): bool
    {
        // Berikan izin HANYA JIKA 'user_id' pada item sama dengan ID pengguna yang sedang login.
        return $user->id === $item->user_id;
    }

    /**
     * Menentukan apakah pengguna dapat menghapus model.
     * Logika ini akan dipanggil oleh $this->authorize('delete', $item);
     */
    public function delete(User $user, Item $item): bool
    {
        // Berikan izin HANYA JIKA 'user_id' pada item sama dengan ID pengguna yang sedang login.
        return $user->id === $item->user_id;
    }

    /**
     * (Opsional) Menentukan apakah pengguna dapat melihat model tertentu.
     */
    public function view(User $user, Item $item): bool
    {
        // Contoh: izinkan jika itu miliknya atau jika dia seorang admin.
        return $user->id === $item->user_id || $user->role === 'admin';
    }
}