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

    /**
     * The table primary key
     *
     * @var string
     */
    protected $primaryKey = 'id_user';

    /**
     * Retrieves user team
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team() {
        return $this->belongsTo('App\Team', 'id_team');
    }

    /**
     * Retrieves the projects where a user is the project manager
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function manager() {
        return $this->hasMany('App\Project', 'id_manager');
    }
}
