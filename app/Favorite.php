<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
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
    protected $table = 'favorite';

    /**
     * Table primary key
     *
     * @var array
     */
    protected $primaryKey = ['id_user', 'id_project'];

    /**
     * Auto-incrementing status
     *
     * @var bool
     */
    public $incrementing = false;

}
