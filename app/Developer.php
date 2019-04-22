<?php

namespace App;

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
        return $this->hasMany('App\Project', 'id_manager');
    }

    /**
     * Retrieves the projects where a user is the project manager
     *
     */
    public static function projectManagement($projects, $id_user) {
        foreach ($projects as $project) {
            $project['manager'] = User::where('id', $id_user)->value('username');
            $project['teams'] = TeamProject::where('id_project', $project['id'])->count();
            $project['tasks_done'] = Task::where([ ['id_project', $project['id']], ['progress', '=', 100]])->count();
            $project['tasks_todo'] = Task::where([ ['id_project', $project['id']], ['progress', '<', 100]])->count();
            $project['favorite'] = Favorite::where([ ['id_project', $project['id']], ['id_user', $id_user]])->exists();
        }

        return $projects;
    }
}
