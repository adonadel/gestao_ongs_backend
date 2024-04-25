<?php

namespace App\Providers;

use App\Policies\AddressPolicy;
use App\Policies\AdoptionPolicy;
use App\Policies\AnimalPolicy;
use App\Policies\EventPolicy;
use App\Policies\FinancePolicy;
use App\Policies\MediaPolicy;
use App\Policies\NgrPolicy;
use App\Policies\PeoplePolicy;
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
        AddressPolicy::class,
        AdoptionPolicy::class,
        EventPolicy::class,
        NgrPolicy::class,
        PeoplePolicy::class,
        FinancePolicy::class,
        AnimalPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Auth::extend('api', function ($app, $name, array $config) {
            return new Guard($app, $name, $config['provider']);
        });
    }
}
