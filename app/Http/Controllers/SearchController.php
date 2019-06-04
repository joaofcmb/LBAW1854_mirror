<?php

namespace App\Http\Controllers;

use App\Project;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Developer;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Search for the specified data.
     *
     * @param  int  $id
     * @return array
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $data = $request->input('data');

        $constraints = $request->input('constraints');
        $constraints = empty($constraints) ? [] : explode(",", $constraints);

        if($data == 'Users')
            return $this->searchUsers(str_replace_first(':* | ', ':A* | ', (str_replace(" ", ':* | ', $query) . ':*')), $constraints);
        else if($data == 'Projects')
            return $this->searchProjects(str_replace_first(':* | ', ':A* | ', (str_replace(" ", ':* | ', $query) . ':*')));
        else
            return $this->searchTeams(str_replace_first(':* | ', ':A* | ', (str_replace(" ", ':* | ', $query) . ':*')), $constraints);
    }

    public function searchUsers($query, $constraints) {        
        array_push($constraints, Auth::user()->getAuthIdentifier());

        $users = User::selectRaw("id, first_name, last_name")
            ->whereRaw("to_tsvector(first_name || ' ' || last_name) @@ to_tsquery('simple', ?)", [$query])
            ->whereNotIn('id', $constraints)
            ->orderByRaw("ts_rank(to_tsvector(first_name || ' ' || last_name), to_tsquery('simple', ?)) DESC", [$query])
            ->get();

        $followers = User::select('user.id')
            ->join('follow', 'follow.id_followee', '=', 'user.id')
            ->where('follow.id_follower', Auth::user()->getAuthIdentifier())
            ->get();       
        
        foreach ($users as $user) {
            $dev = Developer::find($user->id);

            $user->is_active = $dev !== null ? $dev->is_active : true ;
            $user->follow = false;

// Tentar simplificar codigo
            foreach ($followers as $follower) {
                if($user->id == $follower->id)
                    $user->follow = true;
            }
        }
        
        return $users;
    }

    public function searchProjects($query) {

        $projects = Project::selectRaw("id, name, description, color, id_manager")
            ->whereRaw("(setweight(to_tsvector(name), 'A') || setweight(to_tsvector(description), 'B')) @@ to_tsquery('simple', ?)", [$query])
            ->orderByRaw("ts_rank((setweight(to_tsvector(name), 'A') || setweight(to_tsvector(description), 'B')), to_tsquery('simple', ?)) DESC", [$query])
            ->get();
        
        return Project::information($projects);

    }

    public function searchTeams($query, $constraints) {

        $teams = Team::selectRaw("id, name, skill, id_leader")
            ->whereRaw("to_tsvector(name || ' ' || skill) @@ to_tsquery('simple', ?)", [$query])
            ->whereNotIn('id', $constraints)
            ->orderByRaw("ts_rank(to_tsvector(name || ' ' || skill), to_tsquery('simple', ?)) DESC", [$query])
            ->get();

        foreach ($teams as $team) {
            $team['members'] = $team->members;
            $team['leader'] = $team->leader;
        }

        return $teams;

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
