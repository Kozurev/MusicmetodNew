<?php

namespace App\Providers;

use App\Override\Providers\AuthUserProvider;
use Illuminate\Auth\SessionGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /**
         * Custom auth provider
         */
        Auth::extend('custom_auth', function ($app, $name, array $config) {
            $userProvider = app(AuthUserProvider::class);
            return new SessionGuard('web', $userProvider, $this->app['session.store']);
        });
    }
}
