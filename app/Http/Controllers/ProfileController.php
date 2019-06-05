<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Project;
use App\Developer;
use App\Follow;
use App\Team;
use App\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
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
     * @return Factory|View
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
     * @return Factory|View
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
     * @return Factory|RedirectResponse|View
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
     * @return Factory|View
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
            return response("", 404, []);

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

    public function favorite($id_project)
    {
        if(empty(Project::find($id_project)))
            return response("", 404, []);

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
     * Edit the profile information
     * 
     * @param Request $request
     * @param  int  $id
     */
    public function editProfile(Request $request, $id)
    {
        $user = User::find($id);
        $email = $request->input('email');
        $oldPassword = $request->input('oldPassword');
        $newPassword = $request->input('newPassword');
        $biography = $request->input('biography');

        $status = "SUCCESS";

        if(!empty($email)) {
            if($user->email == $email)
                $status = "NOTHING";
            else
                $user->email = $email;
        }


        if(!empty($biography))
            $user->biography = $biography;

        if(!empty($oldPassword) && !empty($newPassword)) {
            if(Hash::check($oldPassword, $user->getAuthPassword()))
                $user->password = Hash::make($newPassword);
            else
                $status = "FAILED";
        }

        $user->save();

        return json_encode(['status' => $status]);
    }

    /**
     * Change the user status to removed
     * 
     * @param int $id
     * @return View
     */
    public function remove($id) 
    {
        try {
            $user = Developer::find($id);
            $user->is_active = false;
            $user->save();
        } catch (Exception $exception) {
            $message = $exception->getMessage();
            $message = substr($message, strrpos($message,"ERROR: "));
            $message = substr($message, 0, strrpos($message, "(SQL"));

            return redirect()->back()->withErrors($message);
        }

        return redirect()->route('admin-users');
    }

    /**
     * Uploads picture
     *
     * @return Response
     */
    public function uploadPicture()
    {
        // Get image ID
        $picture = isset($_FILES['picture']) ? $_FILES['picture'] : null;

        if($picture == null)
            return redirect()->back()->withErrors('Internal error due to changes on html');
        else if($picture['size'] == 0)
            return redirect()->back()->withErrors('Upload picture appears to be corrupted');

        // Determine if it's channel/profile valid image
        $imgInfo['availableExtensions'] = ['jpg', 'png'];

        $imgInfo['type'] = $_POST['type'];
        $imgInfo['directory'] = './img/' . $imgInfo['type'] . '/';
        $imgInfo['extension'] = pathinfo($picture['name'], PATHINFO_EXTENSION);

        // Check if file extension is valid
        if(!in_array($imgInfo['extension'], $imgInfo['availableExtensions'])) {
            return redirect()->back()->withErrors('File extension <' . $imgInfo['extension'] . '> is not valid !');
        }

        // Delete previous image if exists
        for($index = 0; $index < count($imgInfo['availableExtensions']); $index++) {
            if(file_exists($imgInfo['directory'] . Auth::user()->getAuthIdentifier() . '.' . $imgInfo['availableExtensions'][$index])) {
                unlink($imgInfo['directory'] . Auth::user()->getAuthIdentifier() . '.' . $imgInfo['availableExtensions'][$index]);
            }
        }

        // Generate filename
        $filename = $imgInfo['directory'] . Auth::user()->getAuthIdentifier() . '.' . $imgInfo['extension'];

        // Move the uploaded file to its final destination
        move_uploaded_file($picture['tmp_name'], $filename);

        return redirect()->route('profile', ['id' => Auth::user()->getAuthIdentifier()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

}
