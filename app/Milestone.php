<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
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
    protected $table = 'milestone';

    /**
     * Retrieves the project the milestone belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {
        return $this->belongsTo('App\Project', 'id_project');
    }

    /**
     * Get information about milestones
     *
     * @param $milestones
     * @return mixed
     * @throws \Exception
     */
    public static function information($milestones) {

        $currentDate = new DateTime();

        foreach ($milestones as $milestone) {
            $milestone['timeLeft'] = $currentDate->diff(new DateTime($milestone->deadline))->format('%a');
        }

        return $milestones;
    }

}
