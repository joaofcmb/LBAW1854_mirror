<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {
        return $this->belongsTo('App\Project', 'id');
    }

    /**
     * The teams this task belongs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams(){
        return $this->belongsToMany('App\Team', 'team_task', 'id_task', 'id_team');
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
