<?php

namespace App\Http\Controllers;

use App\Project;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Developer;

class SearchController extends Controller
{
    /**
     * Search for the specified data.
     *
     * @param Request $request
     * @return array
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $data = $request->input('data');

        $constraints = $request->input('constraints');
        $constraints = empty($constraints) ? [] : explode(",", $constraints);

        $manager = $request->input('manager');

        if($manager != null && $manager == true) {
            $users = Project::all();

            foreach ($users as $user)
                array_push($constraints, $user->id_manager);
        }

        if($data == 'Users')
            return $this->searchUsers(str_replace_first(':* | ', ':A* | ', (str_replace(" ", ':* | ', $query) . ':*')), $constraints);
        else if($data == 'Projects')
            return $this->searchProjects(str_replace_first(':* | ', ':A* | ', (str_replace(" ", ':* | ', $query) . ':*')));
        else
            return $this->searchTeams(str_replace_first(':* | ', ':A* | ', (str_replace(" ", ':* | ', $query) . ':*')), $constraints);
    }

    /**
     * Search for users with certain constraints
     *
     * @param $query
     * @param $constraints
     * @return mixed
     */
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
            $filename = "img/profile/".$user->id;

            if (file_exists($filename.'.png'))
                $filename = $filename.'.png';
            else if (file_exists($filename.'.jpg'))
                $filename = $filename.'.jpg';
            else
                $filename = 'img/profile.png';

            $dev = Developer::find($user->id);

            $user->is_active = $dev !== null ? $dev->is_active : true ;
            $user->follow = false;
            $user->img_url = $filename;

            foreach ($followers as $follower) {
                if($user->id == $follower->id)
                    $user->follow = true;
            }
        }
        
        return $users;
    }

    /**
     * Search for projects
     *
     * @param $query
     * @return mixed
     */
    public function searchProjects($query) {

        $projects = Project::selectRaw("id, name, description, color, id_manager")
            ->whereRaw("(setweight(to_tsvector(name), 'A') || setweight(to_tsvector(description), 'B')) @@ to_tsquery('simple', ?)", [$query])
            ->orderByRaw("ts_rank((setweight(to_tsvector(name), 'A') || setweight(to_tsvector(description), 'B')), to_tsquery('simple', ?)) DESC", [$query])
            ->get();
        
        return Project::information($projects);
    }

    /**
     * Search for teams with certain constraints
     *
     * @param $query
     * @param $constraints
     * @return mixed
     */
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
}
