<?php

namespace App\Policies;

use App\Project;
use App\User;
use App\Administrator;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class AdministratorPolicy
{
    use HandlesAuthorization;

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Administrator::class => AdministratorPolicy::class,
    ];

    /**
     * Determine whether the user can create administrators.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the administrator.
     *
     * @param  \App\User  $user
     * @param  \App\Administrator  $administrator
     * @return mixed
     */
    public function update(User $user, Administrator $administrator)
    {
        //
    }

    /**
     * Determine whether the user can delete the administrator.
     *
     * @param  \App\User  $user
     * @param  \App\Administrator  $administrator
     * @return mixed
     */
    public function delete(User $user, Administrator $administrator)
    {
        //
    }

    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
