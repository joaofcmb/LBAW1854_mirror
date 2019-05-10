<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
     * Returns the project associated forum
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tasks() {
        return $this->hasMany('App\Task', 'id_project');
    }

    /**
     * Retrieves the forum associated with the project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function forum() {
        return $this->hasOne('App\Forum', 'id_project');
    }

    /**
     * Returns the project associated milestones
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function milestones() {
        return $this->hasMany('App\Milestone', 'id_project')
            ->orderBy('deadline', 'asc')
            ->limit(6);;
    }

    /**
     * Returns the project associated task groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function taskGroups() {
        return $this->hasMany('App\TaskGroup', 'id_project');
    }

    /**
     * Retrieves projects information
     *
     * @param $projects
     * @return
     */
    public static function information($projects) {
        foreach ($projects as $project) {
            $project['manager'] = Project::join('user', 'user.id', '=', 'project.id_manager')->where('project.id', $project['id'])->value('username');
            $project['teams'] = TeamProject::where('id_project', $project->id)->count();
            $project['tasks_todo'] = Task::information(Task::where([['id_project', $project['id']], ['progress', '=', 0]])->get());
            $project['tasks_ongoing'] = Task::information(Task::where([['id_project', $project['id']], ['progress', '!=', 100], ['progress', '!=', 0]])->get());
            $project['tasks_done'] = Task::information(Task::where([['id_project', $project['id']], ['progress', '=', 100]])->get());
            $project['favorite'] = Favorite::where([['id_project', $project['id']], ['id_user', Auth::user()->getAuthIdentifier()]])->exists();
            $project['isLocked'] = Project::isLocked($project);
        }

        return $projects;
    }

    /**
     * Checks if current authenticated user is the manager of a certain project
     *
     * @param $project
     * @return bool
     */
    public static function isProjectManager($project) {
        return $project->id_manager == Auth::user()->getAuthIdentifier();
    }

    /**
     * Checks if a project is locked for the current authenticated user
     *
     * @param $project
     * @return bool
     */
    public static function isLocked($project) {
        return !(Project::where([['id_manager', Auth::user()->getAuthIdentifier()], ['id', $project['id']]])->exists() ||
                 TeamProject::join('developer', 'developer.id_team', '=', 'team_project.id_team')
                     ->where([['developer.id_user', Auth::user()->getAuthIdentifier()], ['id_project', $project['id']]])
                     ->exists());
    }

    /**
     * Retrieves project current milestone
     *
     * @param $project
     * @return mixed
     * @throws \Exception
     */
    public static function getCurrentMilestone($project) {
        $currentDate = new DateTime();
        $date = $currentDate->format('Y-m-d');

        $currentMilestone = Milestone::where([['id_project', $project->id], ['deadline', '>=', $date]])->orderBy('deadline', 'asc')->first();
        
        if(is_object($currentMilestone)) {
            $currentMilestone['timeLeft'] = $currentDate->diff(new DateTime($currentMilestone->deadline))->format('%a');
            $currentMilestone['tasks'] = Task::information(Task::where([['id_milestone', $currentMilestone->id], ['progress', '<', 100]])->get());
      
            return $currentMilestone;
        }
        else
            return null;
    }

    /**
     * Retrieves project milestone
     *
     * @param $milestone_id
     * @return mixed
     * @throws \Exception
     */
    public static function getMilestone($milestone_id) {
        $currentDate = new DateTime();

        $currentMilestone = Milestone::where('id', $milestone_id)->orderBy('deadline', 'asc')->first();
        $currentMilestone['timeLeft'] = $currentDate->diff(new DateTime($currentMilestone->deadline))->format('%a');
        $currentMilestone['tasks'] = Task::information(Task::where([['id_milestone', $currentMilestone->id], ['progress', '<', 100]])->get());

        return $currentMilestone;
    }
}
