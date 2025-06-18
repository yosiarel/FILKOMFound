<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;

class AnnouncementPolicy
{
    /**
     * Izinkan update jika user adalah pemilik ATAU seorang admin.
     */
    public function update(User $user, Announcement $announcement): bool
    {
        return $user->id === $announcement->user_id || $user->role === 'admin';
    }

    /**
     * Izinkan delete jika user adalah pemilik ATAU seorang admin.
     */
    public function delete(User $user, Announcement $announcement): bool
    {
        return $user->id === $announcement->user_id || $user->role === 'admin';
    }
}