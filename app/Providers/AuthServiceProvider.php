<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
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

        // Access tokens will expire after 1 hour
        Passport::tokensExpireIn(now()->addHour());
    
        // Refresh tokens will expire after 2 hours (adjust as needed)
        Passport::refreshTokensExpireIn(now()->addHours(2));
    
        // Personal access tokens will expire after 1 hour
        Passport::personalAccessTokensExpireIn(now()->addHour());
    }
}
