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
        return $this->hasMany('App\Thread', 'id_forum')->orderBy('last_edit_date', 'desc')
            ->select('thread.id', 'title', 'description', 'id_author', 'username as author_name', 'first_name', 'last_name')
            ->join('user', 'user.id', '=', 'id_author')
            ->orderBy('last_edit_date', 'desc');
    }

    /**
     * The project the forum belongs
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project() {
        return $this->belongsTo('App\Project', 'id_project');
    }
}
