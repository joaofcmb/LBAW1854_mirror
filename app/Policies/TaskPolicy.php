<?php

namespace App\Policies;

use App\Developer;
use App\Project;
use App\TaskGroup;
use App\TeamProject;
use App\User;
use App\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the task.
     *
     * @param \App\User $user
     * @param \App\Task $task
     * @param Project $project
     * @return mixed
     */
    public function view(User $user, Task $task, Project $project)
    {
        return TeamProject::where([ ['id_team', Developer::find($user->id)->team->id], ['id_project', $project->id]])->exists() && $task->id_project == $project->id;
    }

    /**
     * Determine whether the user can view the task.
     *
     * @param \App\User $user
     * @param \App\Task $task
     * @param Project $project
     * @return mixed
     */
    public function edit(User $user, Task $task, Project $project)
    {
        return $project->id_manager == $user->id && $task->id_project == $project->id;
    }

    /**
     * Determine whether the user can view the task.
     *
     * @param \App\User $user
     * @param \App\Task $task
     * @param Project $project
     * @return mixed
     */
    public function assign(User $user, Task $task, Project $project)
    {
        return $project->id_manager == $user->id && $task->id_project == $project->id;
    }

    public function group(User $user, Project $project, Task $task, TaskGroup $group) {
        return $project->id_manager == $user->id && $task->id_project == $project->id && $group->id_project == $project->id;
    }

    /**
     * Determine whether the user can create tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the task.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */
    public function update(User $user, Task $task)
    {
        //
    }

    /**
     * Determine whether the user can delete the task.
     *
     * @param  \App\User  $user
     * @param  \App\Task  $task
     * @return mixed
     */
    public function delete(User $user, Task $task)
    {
        //
    }
}
