<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
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
    protected $table = 'project';

    /**
     * Retrieves all teams involved in the project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams() {
        return $this->belongsToMany('App\Team', 'team_project', 'id_project', 'id_team');
    }

    /**
     * Retrieves the project manager
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function manager() {
        return $this->hasOne('App\Developer', 'id_user', 'id_manager');
    }

}
