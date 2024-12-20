<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;

class RequestPolicy
{
    public function update(User $user, Request $request): bool
    {
        return $user->id === $request->book?->user_id;
    }
}
