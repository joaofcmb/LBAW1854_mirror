<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
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
    protected $table = 'comment';

    /**
     * The thread the comment belongs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread() {
        return $this->hasOneThrough('App\Thread', 'App\ThreadComment', 'id_comment', 'id', 'id', 'id_thread');
    }

    /**
     * The task the comment belongs
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function task() {
        return $this->hasOneThrough('App\Thread', 'App\TaskComment', 'id_comment', 'id', 'id', 'id_task');
    }

    /**
     * Retrieve information about comments
     * @param $comments
     * @return mixed
     */
    public static function information($comments) {
        foreach ($comments as $comment) {
            $comment['author'] = User::where('id', $comment['id_author'])->first();
        }

        return $comments;
    }
}
