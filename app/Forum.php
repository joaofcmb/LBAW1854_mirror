<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Forum extends Model
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
    protected $table = 'forum';

    /**
     * The threads associated with the forum
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function threads() {
        return $this->hasMany('App\Thread', 'id_forum');
    }
}
