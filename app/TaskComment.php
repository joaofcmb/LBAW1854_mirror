<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TaskComment extends Pivot
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
    protected $table = 'task_comment';

    /**
     * Table primary key
     *
     * @var array
     */
    protected $primaryKey = ['id_comment', 'id_thread'];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

}
