<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    protected $primaryKey = ['id_follower', 'id_followee'];

    public $incrementing = false;

    /**
     * Retrieves information about follow
     *
     * @param $follows
     * @param $type
     * @return mixed
     */
    public static function information($follows, $type) {
        foreach ($follows as $follow) {
            $follow['followBack'] = Follow::where([['id_follower', '=', Auth::user()->getAuthIdentifier()], ['id_followee', '=', $follow[$type]]])->exists();
        }

        return $follows;
    }
}
