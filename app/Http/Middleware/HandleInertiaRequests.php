<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
       return array_merge(parent::share($request), [
            // Synchronously...
            'appName' => config('app.name'),

                'auth.user.role.permissions' => fn () => $request->user() && $request->user()->role
                ? $request->user()->role->permissions->pluck('name') // Assuming permissions have a `name` attribute
                : null,

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
                
        ]);

    }

    
}