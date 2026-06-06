<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $user = $request->user();

        $cartTotalQuantity = 0;
        if ($user) {
            $cartTotalQuantity = \App\Models\Cart::where('user_id', $user->id)->sum('quantity');
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user(),
            ],
            'canLogin' => app('router')->has('login'),
            'canSignup' => app('router')->has('signup'),
            'user.roles' => $request->user() ? $request->user()->roles->pluck('name') : [],
            'user.permissions' => $request->user() ? $request->user()->getPermissionsViaRoles()->pluck('name') : [],
            'cartTotalQuantity' => $cartTotalQuantity,
            'flash' => [
                'alert' => fn() => $request->session()->get('alert'),
                'alertType' => fn() => $request->session()->get('alertType'),
            ],

        ]);
    }
}
