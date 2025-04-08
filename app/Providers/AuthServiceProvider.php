<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Register your policies here
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // No need to call $this->registerPolicies(); if extending the correct base class

        // Implicitly grant "Super Admin" role all permissions
        // This works আয়নের running checks like ->can() and @can()
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('admin') ? true : null;
        });
    }
}
