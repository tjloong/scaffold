<?php

namespace App\Http\Middleware;

use Inertia\Middleware;
use App\Models\Ability;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app.main';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'app_name' => config('app.name'),
            'app_version' => app_version(),
            'navs' => $this->getNavs($request),
            'flash' => $this->getFlash($request),
            'toast' => $this->getToast($request),
            'alert' => $this->getAlert($request),
            'session' => $request->session()->get('session'),
            'auth.perm' => $this->getPermissions($request),
            'auth.user' => $request->user()
                ? $request->user()->toResource()
                : null,
        ]);
    }

    /**
     * Get flash messages
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getFlash($request)
    {
        $flash = $request->session()->get('flash');

        if ($flash) {
            $split = explode('::', $flash);
            $type = count($split) === 2 ? last($split) : 'info';
            $message = count($split) === 2 ? head($split) : $flash;

            return compact('type', 'message');
        }
        else if ($request->user() instanceof MustVerifyEmail && !$request->user()->hasVerifiedEmail()) {
            return [
                'type' => 'warning', 
                'message' => trans('auth.unverified', ['email' => $request->user()->email]),
                'unverified' => true,
            ];
        }
    }

    /**
     * Get toast message
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function getToast($request)
    {
        if ($toast = $request->session()->get('toast')) {
            $split = explode('::', $toast);
            $type = count($split) === 2 ? last($split) : 'info';
            $message = count($split) === 2 ? head($split) : $toast;
    
            return compact('type', 'message');
        }
    }

    /**
     * Get alert message
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function getAlert($request)
    {
        if ($alert = $request->session()->get('alert')) {
            $split = explode('::', $alert);
            $type = count($split) === 2 ? last($split) : 'info';
            $message = count($split) === 2 ? head($split) : $alert;
    
            return compact('type', 'message');
        }
    }

    /**
     * Get user permissions
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getPermissions($request)
    {
        $permissions = [];

        Ability::all()->each(function ($ability) use ($request, &$permissions) {
            $name = $ability->module . '.' . $ability->name;
            $permissions[$name] = $request->user() && $request->user()->can($name);
        });

        return $permissions;
    }

    /**
     * Get the navigations
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function getNavs($request)
    {
        if (!$request->user()) return [];

        $user = $request->user();
        $route = $request->route()->getName();
        $navs = [
            [
                'label' => 'Dashboard',
                'icon' => 'home-smile',
                'url' => route('dashboard'),
                'active' => $route === 'dashboard',
            ],
            [
                'label' => 'Settings',
                'icon' => 'cog',
                'dropdown' => [
                    [
                        'label' => 'My Account',
                        'url' => route('user.account'),
                        'active' => $route === 'user.account',
                    ],
                    [
                        'label' => 'Teams',
                        'url' => route('team.list'),
                        'active' => in_array($route, ['team.list', 'team.create', 'team.update']),
                        'enabled' => $user && $user->can('team.manage'),
                    ],
                    [
                        'label' => 'Roles',
                        'url' => route('role.list'),
                        'active' => in_array($route, ['role.list', 'role.create', 'role.update']),
                        'enabled' => $user && $user->can('role.manage'),
                    ],
                    [
                        'label' => 'Users',
                        'url' => route('user.list'),
                        'active' => in_array($route, ['user.list', 'user.create', 'user.update']),
                        'enabled' => $user && $user->can('user.manage'),
                    ],
                    [
                        'label' => 'Files',
                        'url' => route('file.list'),
                        'active' => $route === 'file.list',
                    ],
                ],
            ],
        ];

        return collect($navs)
            ->filter(function ($nav) {
                return $nav['enabled'] ?? true;
            })->map(function ($nav) {
                if ($dropdown = $nav['dropdown'] ?? null) {
                    $nav['dropdown'] = collect($nav['dropdown'])->filter(function ($item) {
                        return $item['enabled'] ?? true;
                    })->values();
                }

                return $nav;
            })->values();
    }
}
