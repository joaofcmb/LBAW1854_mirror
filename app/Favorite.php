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
     * The table primary key
     *
     * @var string
     */
    protected $primaryKey = ['id_user', 'id_project'];
}
