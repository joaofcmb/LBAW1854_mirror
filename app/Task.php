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
     * The project this each task belongs
     *
     * @return BelongsTo
     */
    public function project() {
        return $this->belongsTo('App\Project', 'id');
    }

    /**
     * The teams this task belongs
     *
     * @return BelongsToMany
     */
    public function teams(){
        return $this->belongsToMany('App\Team', 'team_task', 'id_task', 'id_team');
    }

    /**
     * The comments the thread has
     *
     * @return HasMany
     */
    public function comments() {
        return $this->hasMany('App\TaskComment', 'id_task')->join('comment', 'id', '=', 'id_comment')->orderBy('creation_date', 'asc');
    }

    /**
     * Retrieves tasks card information
     *
     */
    public static function cardInformation($tasks) {
        foreach ($tasks as $task) {
            $project = Project::where('id', $task['id_project'])->get();

            $task['project_name'] = $project[0]->name;
            $task['color'] = $project[0]->color;
            $task['teams'] = TeamTask::select('id_team')->where('id_task', $task['id'])->count();
            $task['developers'] = TeamTask::join('developer', 'developer.id_team', '=', 'team_task.id_team')->where('id_task', $task['id'])->count();
            $task['deadline'] = Milestone::where('id', $task['id_milestone'])->value('deadline');

        }

        return $tasks;
    }
}
