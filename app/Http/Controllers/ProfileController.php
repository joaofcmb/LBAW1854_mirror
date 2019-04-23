<?php

namespace App\Http\Controllers;

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

        if(Auth::user()->getAuthIdentifier() != $id) {
            if(User::find($id)->isAdmin())
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
        $ownUser = Auth::user()->getAuthIdentifier() != $id ? false : true;

        $team = Developer::find($id)->team;
        $members = Team::teamInformation(Team::find($team->id)->members, $id);

        $leader = User::find($team->id_leader);
        $leader['follow'] = Follow::where([['id_follower', '=', $id], ['id_followee', '=', $leader->id]])->exists();

        return View('pages.profile.profileTeam', ['id' => $id, 'user' => $user, 'ownUser'  => $ownUser, 'team' => $team, 'members' => $members, 'leader' => $leader]);
    }

    public function showFavorites($id) {

        $user = User::find($id);
        $ownUser = Auth::user()->getAuthIdentifier() != $id ? false : true;

        $favorites = Project::join('favorite', 'favorite.id_project', '=', 'project.id')
                            ->where('favorite.id_user', $id)
                            ->get();

        $favorites = Project::cardInformation($favorites, $id);

        return View('pages.profile.profileFavorites', ['id' => $id, 'user' => $user, 'ownUser'  => $ownUser, 'favorites' => $favorites]);
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
