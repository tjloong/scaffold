<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use App\Requests\TeamStoreRequest;
use Illuminate\Support\Facade\Validator;

class TeamController extends Controller
{
    /**
     * Create team
     * 
     * @param TeamStoreRequest $request
     * @return Response
     */
    public function create(TeamStoreRequest $request)
    {
        if ($request->isMethod('post')) return $this->store($request);

        return inertia('settings/team/create');
    }

    /**
     * Update team
     * 
     * @param TeamStoreRequest $request
     * @return Response
     */
    public function update(TeamStoreRequest $request)
    {
        if ($request->isMethod('post')) return $this->store($request);

        $tab = $request->tab ?? 'users';
        $team = Team::findOrFail($request->id);
        $users = User::teamId($team->id)->fetch();

        return inertia('settings/team/update', [
            'tab' => $tab,
            'team' => $team->toResource(),
            'users' => $users,
        ]);
    }

    /**
     * List teams
     *
     * @return Response
     */
    public function list()
    {
        $teams = Team::fetch();

        if (request()->isMethod('post')) return back()->with('session', $teams);

        return inertia('settings/team/list', [
            'teams' => $teams,
            'can.create' => request()->user()->can('team.manage'),
        ]);
    }

    /**
     * Store team
     *
     * @param TeamStoreRequest $request
     * @return TeamResource
     */
    public function store($request)
    {
        $request->validated();

        $team = Team::findOrNew($request->id);

        $team->fill($request->input('team'))->save();

        return $request->id
            ? back()->with('toast', 'Team Updated::success')
            : redirect()->route('team.update', ['id' => $team->id])->with('toast', 'Team Created::success');
    }

    /**
     * Delete team
     *
     * @return Response
     */
    public function delete()
    {
        if (Team::whereIn('id', explode(',', request()->id))->has('users')->count() > 0) {
            return back()->with('alert', 'There are users assigned under this team::alert');
        }

        Team::whereIn('id', explode(',', request()->id))->delete();

        return redirect()->route('team.list')->with('toast', 'Team Deleted');
    }
}
