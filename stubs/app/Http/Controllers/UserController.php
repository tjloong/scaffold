<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Team;
use App\Models\Ability;
use App\Requests\UserStoreRequest;

class UserController extends Controller
{
    /**
     * User account
     * 
     * @return Response
     */
    public function account()
    {
        if (request()->isMethod('post')) {
            $user = request()->user();

            $user->fill(request()->all())->save();

            return back()->with('toast', 'Account Updated::success');
        }

        return inertia('settings/account');
    }

    /**
     * Create user
     * 
     * @param UserStoreRequest $request
     * @return Response
     */
    public function create(UserStoreRequest $request)
    {
        if ($request->isMethod('post')) return $this->store($request);

        $roles = Role::fetch();

        return inertia('settings/user/create', [
            'roles' => $roles,
        ]);
    }

    /**
     * Update user
     * 
     * @param UserStoreRequest $request
     * @return Response
     */
    public function update(UserStoreRequest $request)
    {
        if ($request->isMethod('post')) return $this->store($request);

        $tab = $request->tab ?? 'abilities';
        $user = User::findOrFail($request->id);
        $roles = Role::fetch();
        $teams = Team::userId($user->id)->fetch();
        $abilities = $tab === 'abilities' 
            ? Ability::all()->map(function($ability) use ($user) {
                $ability->enabled = $user->can($ability->module . '.' . $ability->name);
                return $ability;
            })
            : null;

        return inertia('settings/user/update', [
            'tab' => $tab,
            'user' => $user->toResource(),
            'roles' => $roles,
            'teams' => $teams,
            'abilities' => $abilities,
        ]);
    }

    /**
     * User list
     * 
     * @return Response
     */
    public function list()
    {
        $users = User::fetch();

        if (request()->isMethod('post')) return back()->with('session', $users);

        return inertia('settings/user/list', [
            'users' => $users,
            'can.create' => request()->user()->can('user.manage'),
        ]);
    }

    /**
     * Store user
     * 
     * @param UserStoreRequest $request
     * @return Response
     */
    public function store(UserStoreRequest $request)
    {
        $request->validated();

        $user = User::findOrNew($request->id);

        $user->fill($request->input('user'))->save();

        if ($request->has('user.abilities')) {
            $user->abilities()->sync($request->input('user.abilities'));
        }

        if ($request->has('user.teams')) {
            $user->teams()->sync($request->input('user.teams'));
        }

        if ($request->id) return back()->with('toast', 'User Updated::success');
        else {
            $user->invite();    
            return redirect()->route('user.update', ['id' => $user->id])->with('toast', 'User Created::success');
        }
    }

    /**
     * Send account activation invitation
     * 
     * @return Response
     */
    public function invite()
    {
        $user = User::findOrFail(request()->id);

        $user->invite();

        return back()->with('toast', 'Email Sent::success');
    }

    /**
     * Delete user
     * 
     * @return Response
     */
    public function delete()
    {
        $users = User::where('id', '<>', request()->user()->id)
            ->whereIn('id', explode(',', request()->id))
            ->delete();

        return redirect()->route('user.list')->with('toast', 'User Deleted');
    }
}