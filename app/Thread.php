<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * @return BelongsTo
     */
    public function forum() {
        return $this->belongsTo('App\Forum', 'id_forum');
    }

    /**
     * The comments the thread has
     *
     * @return HasMany
     */
    public function comments() {
        return $this->hasMany('App\ThreadComment', 'id_thread')->join('comment', 'id', '=', 'id_comment')->orderBy('creation_date', 'asc');
    }

    public static function threadInformation($threads) {
        foreach ($threads as $thread) {
            $thread['author_name'] = User::where('id', $thread['id_author'])->value('username');
        }

        return $threads;
    }
}
