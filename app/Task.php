<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
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
    protected $table = 'task';

    /**
     * The project this task belongs
     *
     * @return BelongsTo
     */
    public function project() {
        return $this->belongsTo('App\Project', 'id');
    }

    /**
     * The task group this task belongs
     *
     * @return BelongsTo
     */
    public function taskGroup() {
        return $this->belongsTo('App\TaskGroup', 'id_group');
    }

    /**
     * The teams this task belongs
     *
     * @return BelongsToMany
     */
    public function teams(){
        return $this->belongsToMany('App\Team', 'team_task', 'id_task', 'id_team');
    }

    public function milestone() {
        return $this->belongsTo('App\Milestone', 'id_milestone');
    }

    /**
     * The comments the thread has
     *
     * @return HasMany
     */
    public function comments() {
        return $this->hasMany('App\TaskComment', 'id_task')
            ->select('id_comment', 'text', 'id_author', 'user.username', 'first_name', 'last_name')
            ->join('comment', 'id', '=', 'id_comment')
            ->join('user', 'user.id', '=', 'comment.id_author')
            ->orderBy('creation_date', 'asc');
    }

    /**
     * Retrieves tasks card information
     */
    public static function information($tasks) {
        foreach ($tasks as $task) {
            $project = Project::where('id', $task['id_project'])->get();

            $task['project_name'] = $project[0]->name;
            $task['color'] = $project[0]->color;
            $task['teams'] = $task->teams;
            $task['developers'] = TeamTask::join('developer', 'developer.id_team', '=', 'team_task.id_team')->where('id_task', $task['id'])->count();

            $currentDate = new DateTime(date('Y/m/d'));
            $creationDate = new DateTime($task['creation_date']);
            $deadline = new DateTime(Milestone::where('id', $task['id_milestone'])->value('deadline'));

            $deltaTime = $creationDate->diff($currentDate)->format('%a');
            $totalTime = $creationDate->diff($deadline)->format('%a');
            $timeLeft = $currentDate->diff($deadline)->format('%R%a');

            if(substr($timeLeft,0,1) == "-"){
                $task['timeLeft'] = 0;
                $task['timePercentage'] = 100;
            } else {
                $task['timeLeft'] = substr($timeLeft,1);
                $task['timePercentage'] = $totalTime == 0 ? 100 : $deltaTime * 100 / $totalTime;
            }
        }

        return $tasks;
    }
}
