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

    public static function followInformation($follows) {
        foreach ($follows as $follow) {
            $follow['followBack'] = Follow::where([['id_follower', '=', $follow['id_followee']], ['id_followee', '=', $follow['id_follower']]])->exists();
        }

        return $follows;
    }
}
