<?php

namespace App;

class Developer extends User
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
    protected $table = 'developer';

    protected $primaryKey = 'id_user';

    public function team() {
        $this->hasOne('App\Team', 'id');
    }

    public function manager() {
        return $this->hasMany('App\Project', 'id_manager');
    }
}
