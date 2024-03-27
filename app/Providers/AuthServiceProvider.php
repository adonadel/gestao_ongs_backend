<?php

namespace App\Providers;

use App\Policies\MediaPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Guard;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        UserPolicy::class,
        MediaPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Auth::extend('api', function ($app, $name, array $config) {
            return new Guard($app, $name, $config['provider']);
        });
    }
}
