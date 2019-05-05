<?php

namespace App\Policies;

use App\Developer;
use App\TeamProject;
use App\Thread;
use App\User;
use App\Project;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function view(User $user, Project $project)
    {
        return $user->isAdmin() || !Project::isLocked($project);
    }

    /**
     * Determine whether the user can create projects.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function createTask(User $user, Project $project)
    {
        return $project->id_manager == $user->id;
    }

    /**
     * Determine whether the user can create threads on project forum.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function createThread(User $user, Project $project)
    {
        return $project->id_manager == $user->id || TeamProject::join('team', 'team.id', '=', 'team_project.id_team')->where([['team.id_leader', $user->id], ['id_project', $project->id]])->exists();
    }

    /**
     * Determine whether the user can add comments on project forum threads
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function addComment(User $user, Project $project)
    {
        return $project->id_manager == $user->id || TeamProject::join('team', 'team.id', '=', 'team_project.id_team')->where([['team.id_leader', $user->id], ['id_project', $project->id]])->exists();
    }

    /**
     * Determine whether the user can update the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function update(User $user, Project $project)
    {
        //
    }

    /**
     * Determine whether the user can delete the project.
     *
     * @param  \App\User  $user
     * @param  \App\Project  $project
     * @return mixed
     */
    public function delete(User $user, Project $project)
    {
        //
    }

    /**
     * Determine whether the user can delete the project forum thread.
     *
     * @param User $user
     * @param Project $project
     * @param Thread $thread
     * @return bool
     */
    public function deleteForumThread(User $user, Project $project, Thread $thread)
    {
        return $user->id == $project->id_manager || $user->id == $thread->id_author;
    }
}
