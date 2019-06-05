<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamTask extends Pivot
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
    protected $table = 'team_task';

    /**
     * Table primary key
     *
     * @var array
     */
    protected $primaryKey = ['id_team', 'id_task'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;


}