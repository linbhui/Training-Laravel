<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::with(['creator', 'updater'])->paginate(10);

        $sort = $request->input('sort');
        if (!empty($sort)) {
            $query = Team::with(['creator', 'updater']);
            $query->orderBy('name', $sort);
            $teams = $query->paginate(10)->appends(['sort' => $sort]);
        }

        return view('management.contents.list_team', compact('teams'));
    }

    public function add()
    {
        return view('management.contents.add_team');
    }

    public function add_confirm(Request $request)
    {
        $rawInput = $request->input('name');

        $withoutTeam = preg_replace('/^team\s*/i', '', trim($rawInput));

        $formattedName = 'Team ' . ucwords(strtolower($withoutTeam));

        if (Team::where('name', $formattedName)->exists()) {
            return back()->withErrors([
                'name' => $formattedName . ' already exists.'
            ])->withInput();
        }

        Team::create([
            'name' => $formattedName,
            'ins_id' => session('id')
        ]);

        return redirect()->route('team.index')->with('notification', 'New team added successfully.');
    }

    public function edit(string $id)
    {
        $team = Team::findOrFail($id);

        return view('management.contents.edit_team', [
            'teamName' => $team->name,
            'teamId' => $team->id
        ]);
    }

    public function edit_confirm(Request $request)
    {
        $rawInput = $request->input('name');

        $withoutTeam = preg_replace('/^team\s*/i', '', trim($rawInput));

        $formattedName = 'Team ' . ucwords(strtolower($withoutTeam));

        $team = Team::findOrFail($request->input('id'));
        $name = Team::where('name', $formattedName)->first();

        if ($name && $team->id != $name->id) {
            return back()->withErrors([
                'name' => $formattedName . ' already exists.'
            ]);
        }

        $team->update([
            'name' => $formattedName,
            'upd_id' => session('id'),
            'upd_datetime' => now()
        ]);

        return redirect()->route('team.index')->with('notification', 'Updated team successfully.');
    }

    public function delete(string $id)
    {
        $team = Team::findOrFail($id);
        $team->update([
            'upd_id' => session('id'),
            'upd_datetime' => now(),
            'del_flag' => 1
        ]);

        return redirect()->route('team.index')->with('notification', 'Deleted team successfully.');
    }

    public function recover(string $id)
    {
        $team = Team::findOrFail($id);
        $team->update([
            'upd_id' => session('id'),
            'upd_datetime' => now(),
            'del_flag' => 0
        ]);
        return redirect()->route('team.index')->with('notification', 'Recovered team successfully.');
    }

    public function search(Request $request)
    {
        $searchBy = $request->input('by');
        $search = $request->input('search');
        $teams = [];
        $result = '';

        if ($searchBy === 'group') {
            if ($search === 'active') {
                $teams = Team::where('del_flag', 0)->with(['creator', 'updater'])->paginate(10);
                $result = "Active teams:";
            } elseif ($search === 'deactivated') {
                $teams = Team::where('del_flag', 1)->with(['creator', 'updater'])->paginate(10);
                $result = "Deactivated teams:";
            }
        } else {
            $teams = Team::where('del_flag', 0)->where('name', 'LIKE', "%{$search}%")->with(['creator', 'updater'])->paginate(10);
            if (empty($teams)) {
                $result = "No team found.";
            } else {
                $result = "Results for: " . $search;
            }
        }

        return view('management.contents.list_team', compact('teams', 'result'));
    }
}
