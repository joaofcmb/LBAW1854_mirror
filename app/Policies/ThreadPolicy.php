<?php

namespace App\Policies;

use App\User;
use App\Thread;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the thread.
     *
     * @param  \App\User  $user
     * @param  \App\Thread  $thread
     * @return mixed
     */
    public function delete(User $user, Thread $thread)
    {
        return $user->isAdmin() || $thread->id_author == $user->id;
    }
}
