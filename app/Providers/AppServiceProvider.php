<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\Relation;
use App\Models\Tshirt;
use App\Models\Jogger;
use App\Models\User;

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
        Inertia::share([
            'auth' => fn() => [
                'user' => Auth::user(),
            ],
        ]);

        Relation::enforceMorphMap([
            'TSHIRT' => Tshirt::class,
            'JOGGER' => Jogger::class,
            'USER' => User::class,
        ]);
    }
}
