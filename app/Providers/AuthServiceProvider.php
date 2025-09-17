<?php

namespace App\Providers;

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

        // Configure Passport
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        // Define token scopes for different access levels
        Passport::tokensCan([
            'read-projects' => 'Read projects',
            'write-projects' => 'Create and update projects',
            'delete-projects' => 'Delete projects',
            'manage-collaborators' => 'Manage project collaborators',
            'read-analytics' => 'Read analytics data',
            'manage-vendors' => 'Manage vendor operations',
            'admin' => 'Full administrative access',
        ]);

        // Set default scope
        Passport::setDefaultScope([
            'read-projects',
        ]);
    }
}
