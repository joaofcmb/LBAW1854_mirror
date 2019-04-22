<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
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
    protected $table = 'thread';

    /**
     * The forum associated with the thread
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function forum() {
        return $this->belongsTo('App\Forum', 'id_forum');
    }

    public static function threadInformation($threads) {
        foreach ($threads as $thread) {
            $thread['author_name'] = User::where('id', $thread['id_author'])->value('username');
        }

        return $threads;
    }
}
