<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ThreadComment extends Pivot
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
    protected $table = 'thread_comment';

    /**
     * Table primary key
     *
     * @var array
     */
    protected $primaryKey = ['id_comment', 'id_thread'];

    /**
     * Auto-incrementing status
     *
     * @var bool
     */
    public $incrementing = false;

}
