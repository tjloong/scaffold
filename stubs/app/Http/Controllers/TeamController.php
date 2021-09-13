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
     * @return Response
     */
    public function create()
    {
        return inertia('settings/team/create');
    }

    /**
     * Edit team
     * 
     * @return Response
     */
    public function edit()
    {
        $tab = request()->tab ?? 'users';
        $team = Team::findOrFail(request()->id);
        $users = User::teamId($team->id)->fetch();

        return inertia('settings/team/edit', [
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

        if (request()->isMethod('post')) return back()->with('options', $teams);

        return inertia('settings/team/list', [
            'teams' => $teams,
            'can.create' => request()->user()->can('settings-team.manage'),
        ]);
    }

    /**
     * Store team
     *
     * @param TeamStoreRequest $request
     * @return TeamResource
     */
    public function store(TeamStoreRequest $request)
    {
        $request->validated();

        $team = Team::findOrNew($request->id);

        $team->fill($request->all())->save();

        if (!$request->id) return redirect()->route('team.edit', ['id' => $team->id])->with('toast', 'Team Created::success');

        return back()->with('toast', 'Team Updated::success');
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
