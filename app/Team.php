<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Team extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team';

    /**
     * Retrieves all team members
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function members() {
        return $this->hasMany('App\Developer', 'id_team')
            ->select('user.id', 'user.username')
            ->join('user', 'user.id', '=', 'developer.id_user')
            ->where([['id_user', '!=', $this->id_leader]]);
    }

    /**
     * Retrieves team leader
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function leader() {
        return $this->hasOne('App\Developer', 'id_user', 'id_leader')
            ->select('user.id', 'user.username')
            ->join('user', 'user.id', '=', 'developer.id_user');
    }

    /**
     * Retrieves all projects in which the team is involved in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects() {
        return $this->belongsToMany('App\Project', 'team_project', 'id_team', 'id_project')
            ->where('project.id_manager', '!=', Auth::user()->getAuthIdentifier());
    }

    /**
     * Retrieves all tasks associated which the team is working on
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tasks() {
        return $this->belongsToMany('App\Task', 'team_task', 'id_team', 'id_task');
    }

    /**
     * Checks following status of team members and team leader
     *
     * @param $team
     * @return mixed
     */
    public static function information($team) {

        foreach ($team->members as $member) {
            $member['follow'] = Follow::where([['id_follower', Auth::user()->getAuthIdentifier()], ['id_followee',  $member->id]])->exists();
        }

        $team->leader['follow'] = Follow::where([['id_follower', Auth::user()->getAuthIdentifier()], ['id_followee', $team->leader->id]])->exists();

        return $team;
    }

}
