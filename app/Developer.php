<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class Developer extends User
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
    protected $table = 'developer';

    /**
     * The table primary key
     *
     * @var string
     */
    protected $primaryKey = 'id_user';

    /**
     * Retrieves user team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team() {
        return $this->belongsTo('App\Team', 'id_team');
    }

    /**
     * Retrieves the projects where a user is the project manager
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function manager() {
        return $this->hasMany('App\Project', 'id_manager')
            ->where('status', '=', 'active');
    }

    /**
     * Checks if current authenticated user can comment on a task or not
     *
     * @param $task
     * @return mixed
     */
    public static function canAddTaskComment($task) {
        return Developer::join('team_task', 'team_task.id_team', '=', 'developer.id_team')
            ->where([['team_task.id_task', $task->id], ['developer.id_user', Auth::user()->getAuthIdentifier()]])
            ->exists();
    }
}
