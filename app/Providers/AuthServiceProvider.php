<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Requisicao::class => \App\Policies\RequisicaoPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Define o gate 'admin'
        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
