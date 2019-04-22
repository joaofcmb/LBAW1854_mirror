<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
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
    protected $table = 'follow';

    /**
     * The table primary key
     *
     * @var string
     */
    protected $primaryKey = ['id_follower', 'id_followee'];
}
