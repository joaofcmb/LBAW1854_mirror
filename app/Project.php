<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
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
    protected $table = 'project';

    /**
     * Retrieves all teams involved in the project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams() {
        return $this->belongsToMany('App\Team', 'team_project', 'id_project', 'id_team');
    }

    /**
     * Retrieves the project manager
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function manager() {
        return $this->hasOne('App\Developer', 'id_user', 'id_manager');
    }

    /**
     * Retrieves the tasks involved in this project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks() {
        return $this->hasMany('App\Task', 'id_project');
    }

    public function forum() {
        return $this->hasOne('App\Forum', 'id_project');
    }

    public function milestones() {
        return $this->hasMany('App\Milestone', 'id_project');
    }

    /**
     * Retrieves projects card information
     *
     */
    public static function cardInformation($projects, $id_user) {
        foreach ($projects as $project) {
            $project['manager'] = Project::join('user', 'user.id', '=', 'project.id_manager')->where('project.id', $project['id'])->value('username');
            $project['teams'] = TeamProject::where('id_project', $project['id'])->count();
            $project['num_tasks_done'] = Task::where([['id_project', $project['id']], ['progress', '=', 100]])->count();
            $project['num_tasks_todo'] = Task::where([['id_project', $project['id']], ['progress', '<', 100]])->count();
            $project['favorite'] = Favorite::where([['id_project', $project['id']], ['id_user', $id_user]])->exists();
            $project['lock'] = Project::where([['id_manager', $id_user], ['id', $project['id']]])->exists() || TeamProject::join('developer', 'developer.id_team', '=', 'team_project.id_team')->where([['developer.id_user', $id_user], ['id_project', $project['id']]])->exists();
        }

        return $projects;
    }

    public static function projectInformation($project) {

        $project['tasks_todo'] = Task::cardInformation(Task::where([['id_project', $project['id']], ['progress', '=', 0]])->get());
        $project['tasks_ongoing'] = Task::cardInformation(Task::where([['id_project', $project['id']], ['progress', '!=', 100], ['progress', '!=', 0]])->get());
        $project['tasks_done'] = Task::cardInformation(Task::where([['id_project', $project['id']], ['progress', '=', 100]])->get());
 
        return $project;
    }


}
