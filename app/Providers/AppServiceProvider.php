<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('edit-product', function (User $user, Product $product) {
            return $product->team->user->is($user);
        });
        Gate::define('create-product', function (User $user, Team $team) {
            return $team->user->is($user);
        });
        Gate::define('isOwner', function (User $user) {
            return $user->currentTeam->owner->is($user);
        });
    }
}
