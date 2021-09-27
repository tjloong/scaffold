<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Ability;
use App\Requests\RoleStoreRequest;

class RoleController extends Controller
{
    /**
     * Create role
     * 
     * @param RoleStoreRequest $request
     * @return Response
     */
    public function create(RoleStoreRequest $request)
    {
        if ($request->isMethod('post')) return $this->store($request);

        $clonables = Role::fetch();

        return inertia('settings/role/create', [
            'clonables' => $clonables,
        ]);
    }

    /**
     * Update role
     * 
     * @param RoleStoreRequest $request
     * @return Response
     */
    public function update(RoleStoreRequest $request)
    {
        if ($request->isMethod('post')) return $this->store($request);

        $tab = $request->tab ?? 'abilities';
        $role = Role::findOrFail($request->id);
        $users = $tab === 'users' ? $role->users()->fetch() : null;
        $abilities = $tab === 'abilities' 
            ? Ability::all()->map(function($ability) use ($role) {
                $ability->enabled = $role->abilities()->where('abilities.id', $ability->id)->count() > 0
                    || $role->isAdmin();

                return $ability;
            })
            : null;

        return inertia('settings/role/update', [
            'tab' => $tab,
            'role' => $role->toResource(),
            'abilities' => $abilities,
            'users' => $users,
        ]);
    }

    /**
     * Role list
     *
     * @return Response
     */
    public function list()
    {
        $roles = Role::fetch();

        return inertia('settings/role/list', [
            'roles' => $roles,
            'can.create' => request()->user()->can('role.manage'),
        ]);
    }

    /**
     * Store role
     *
     * @param RoleStoreRequest $request
     * @return RoleResource
     */
    public function store(RoleStoreRequest $request)
    {
        $request->validated();

        $role = Role::findOrNew($request->id);

        $role->fill($request->input('role'))->save();

        if ($request->has('role.clone_from_id') && !$request->has('id')) {
            if ($source = Role::find($request->input('role.clone_from_id'))) {
                $role->abilities()->sync($source->abilities->pluck('id')->toArray());
            }
        }

        if ($request->has('role.abilities')) {
            $role->abilities()->sync($request->input('role.abilities'));
        }

        return $request->id
            ? back()->with('toast', 'Role Updated::success')
            : redirect()->route('role.update', ['id' => $role->id])->with('toast', 'Role Created::success');
    }

    /**
     * Delete role
     *
     * @return Response
     */
    public function delete()
    {
        if (Role::whereIn('id', explode(',', request()->id))->has('users')->count() > 0) {
            return back()->with('alert', 'There are users assigned under this role::alert');
        }

        Role::whereIn('id', explode(',', request()->id))->delete();

        return redirect()->route('role.list')->with('toast', 'Role Deleted');
    }
}
