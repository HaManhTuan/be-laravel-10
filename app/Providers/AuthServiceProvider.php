<?php

namespace App\Providers;

use App\Constants\AppConst;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        Passport::tokensExpireIn(now()->addDays(AppConst::TOKEN_EXPIRES_TIME));
        Passport::refreshTokensExpireIn(now()->addDays(AppConst::REFRESH_TOKEN_EXPIRES_TIME));
    }
}
