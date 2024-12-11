<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
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
            Inertia::share('auth.user', function () {
                $user = Auth::user();
            
                if ($user instanceof \App\Models\User){
                    $user->load(['role.permissions']); // Ensure role and permissions are loaded
            
                    return [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role ? [
                            'id' => $user->role->id,
                            'name' => $user->role->name,
                            'permissions' => $user->role->permissions->map(function ($permission) {
                                return [
                                    'id' => $permission->id,
                                    'name' => $permission->name,
                                ];
                            }),
                        ] : null,
                    ];
                }
            
                return null;
            });                        
            
        }        
}