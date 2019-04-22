<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany('App\Developer', 'id_team');
    }

    /**
     * Retrieves team leader
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function leader() {
        return $this->hasOne('App\Developer', 'id_user', 'id_leader');
    }

    /**
     * Retrieves all projects in which the team is involved in
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects() {
        return $this->belongsToMany('App\Project', 'team_project', 'id_team', 'id_project');
    }

    /**
     * Retrieves all tasks associated which the team is working on
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tasks() {
        return $this->belongsToMany('App\Task', 'team_task', 'id_team', 'id_task');
    }

}
