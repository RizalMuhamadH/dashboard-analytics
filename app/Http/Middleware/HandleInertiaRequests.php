<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        $user = $request->user();
        return array_merge(parent::share($request), [
            'data' => $request->session()->get('data'),
            'success' => [
                'message' => Session::get('message')
            ],
            'auth' => [
                'user' => [
                    '_id' => $user->_id ?? '',
                    'name' => $user->name ?? '',
                    'email' => $user->email ?? ''
                ],
                'roles' => $user ? $user->getRoleNames() : [],
                'permissions' => $user ? $user->getPermissionNames() : []
            ],
        ]);
    }
}
