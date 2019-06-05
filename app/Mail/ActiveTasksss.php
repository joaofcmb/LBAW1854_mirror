<?php

namespace App\Mail;

use App\Developer;
use App\Forum;
use App\Project;
use App\Task;
use App\Team;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class ActiveTasksss extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The user instance.
     *
     * @var User
     */
    public $userID;

    /**
     * Create a new message instance.
     *
     * @param $userID
     */
    public function __construct($userID)
    {
        $this->userID = $userID;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = Developer::find($this->userID);
        $team = Team::find($user->id_team);

        return $this->view('emails.activeTasks', ['teamTasks' => Task::information($team->tasks)]);
    }
}
