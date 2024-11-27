<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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
            // Use eager loading on the Auth user retrieval directly
        Auth::viaRequest('custom-token', function ($request) {
            return User::with('role')->find(Auth::id()); // Eager load the role relationship
        });

        Inertia::share('auth.user', function () {
            $user = Auth::user();
            
            if ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role->name ?? null,
                ];
            }
            return null;
        });
    }
}
