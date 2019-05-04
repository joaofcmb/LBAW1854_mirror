<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Project;
use App\Developer;
use App\Follow;
use App\Team;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if(empty($user))
            return redirect()->route('404');

        if(Auth::user()->getAuthIdentifier() != $id) {
            if($user->isAdmin())
                return redirect()->route('profile-favorites', ['id' => $id]);
            else
                return redirect()->route('profile-team', ['id' => $id]);
        }

        return View('pages.profile.profileInfo', ['id' => $id, 'user' => Auth::user()]);
    }

    /**
     * Displays user profile team section
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showTeam($id) {

        $user = User::find($id);

        if(empty($user) || $user->isAdmin())
            return redirect()->route('404');

        $team = Developer::find($id)->team;

        if (isset($team)) {
            $team['members'] = $team->members;
            $team['leader'] = $team->leader;

            return View('pages.profile.profileTeam', ['id' => $id,
                'user' => $user,
                'ownUser'  => Auth::user()->getAuthIdentifier() == $id,
                'team' => Team::information($team)
            ]);
        }
        else {
            return View('pages.profile.profileTeam', ['id' => $id,
                'user' => $user,
                'ownUser'  => Auth::user()->getAuthIdentifier() == $id
            ]);
        }
    }

    /**
     * Displays user profile favorites section
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFavorites($id)
    {
        $user = User::find($id);

        if(empty($user))
            return redirect()->route('404');

        $favorites = Project::join('favorite', 'favorite.id_project', '=', 'project.id')
            ->where('favorite.id_user', $id)
            ->get();

        return View('pages.profile.profileFavorites', ['id' => $id,
                          'user' => $user,
                          'ownUser'  => Auth::user()->getAuthIdentifier() == $id,
                          'favorites' => Project::information($favorites)
        ]);
    }

    /**
     * Displays user profile followers section
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function showFollowers($id)
    {
        $user = User::find($id);

        if(empty($user))
            return redirect()->route('404');

        $followers = User::join('follow', 'follow.id_follower', '=', 'user.id')
            ->where('follow.id_followee', $id)
            ->get();

        return View('pages.profile.profileFollow', ['id' => $id,
                          'user' => $user,
                          'ownUser'  => Auth::user()->getAuthIdentifier() == $id,
                          'follow' => Follow::information($followers, 'id_follower'),
                          'type' => 'followers'
        ]);
    }

    /**
     * Displays user profile following section
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFollowing($id)
    {
        $user = User::find($id);

        if(empty($user))
            return redirect()->route('404');

        $following = User::join('follow', 'follow.id_followee', '=', 'user.id')
            ->where('follow.id_follower', $id)
            ->get();

        return View('pages.profile.profileFollow', ['id' => $id,
                                                          'user' => $user,
                                                          'ownUser'  => Auth::user()->getAuthIdentifier() == $id,
                                                          'follow' =>  Follow::information($following, 'id_followee'),
                                                          'type' => 'following'
            ]);
    }

    /**
     * Follows/Unfollows a user
     *
     * @param Request $request
     * @param $id_user
     * @return false|string
     */
    public function follow($id_user)
    {
        if(empty(User::find($id_user)))
            return response("", 403, []);

        if(Follow::where([['id_follower', Auth::user()->getAuthIdentifier()],['id_followee', $id_user]])->exists()) {
            Follow::where([['id_follower', Auth::user()->getAuthIdentifier()],['id_followee', $id_user]])->delete();
        }
        else{
            $follow = new Follow();

            $follow->id_follower = Auth::user()->getAuthIdentifier();
            $follow->id_followee = (integer)$id_user;

            $follow->save();
        }

    }

    public function favorite(Request $request, $id_project)
    {
        if(empty(Project::find($id_project)))
            return response("", 403, []);

        if (Favorite::where([['id_user', Auth::user()->getAuthIdentifier()], ['id_project', $id_project]])->exists()) {
            Favorite::where([['id_user', Auth::user()->getAuthIdentifier()], ['id_project', $id_project]])->delete();
        } else {
            $favorite = new Favorite();

            $favorite->id_user = Auth::user()->getAuthIdentifier();
            $favorite->id_project = $id_project;

            $favorite->save();
        }

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
