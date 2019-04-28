<?php

namespace App\Http\Controllers;

use App\Project;
use App\Developer;
use App\Follow;
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
        $team['members'] = $team->members;
        $team['leader'] = $team->leader;

        return View('pages.profile.profileTeam', ['id' => $id,
                                                        'user' => $user,
                                                        'ownUser'  => Auth::user()->getAuthIdentifier() == $id,
                                                        'team' => $team
        ]);
    }

    /**
     * Displays user profile followers section
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showFavorites($id) {

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

    public function showFollowers($id) {

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
    public function showFollowing($id) {

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
