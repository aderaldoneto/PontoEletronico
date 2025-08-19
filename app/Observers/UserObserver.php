<?php

namespace App\Observers;

use App\Models\User;
use Filament\Panel;

class UserObserver
{
    public function creating(User $user): void
    {
        if (! $user->created_by) {
            $user->created_by = auth()->id();
        }

    }
}
