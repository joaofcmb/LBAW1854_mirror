@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
@endsection

@section('title', 'Project - Tasks')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => '',
            'auth' => 'session'
        ])

        @include('partials.sub-navbar', [
            'active' => 'tasks',
            'project' => $project,
            'isProjectManager' => $isProjectManager
        ])
    </div>

    <div id="task-groups" class="container-fluid d-flex flex-nowrap overflow-auto py-3 mb-4 mr-3">
        @include('partials.taskGroup', [
            'isProjectManager' => $isProjectManager,
            'title' => 'Ungrouped Tasks',
            'tasks' => $projectUngroupedTasks,
            'project' => $project,
            'ungrouped' => true
        ])

        @foreach($projectTaskGroups as $taskGroup)
            @include('partials.taskGroup', [
                'isProjectManager' => $isProjectManager,
                'title' => $taskGroup->title,
                'tasks' => $taskGroup->tasks,
                'project' => $project,
                'ungrouped' => false,
                'taskGroup' => $taskGroup
            ])
        @endforeach

        @if($isProjectManager)
            <div class="main-tab flex-shrink-0 card open border-left-0 border-right-0 rounded-0 py-2 mr-3 mb-auto">
                <button id="add-group" class="my-1 mx-5" type="button" data-toggle="modal" data-target="#addTaskGroupModal">
                    <h4 class="m-0">Add Group <i class="fas fa-plus-circle"></i></h4>
                </button>
            </div>
            <!-- Modal -->
            <div class="modal fade" id="addTaskGroupModal" tabindex="-1" role="dialog" aria-labelledby="addTaskGroupModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addTaskGroupModalTitle">Add Task Group</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="task-group-title" class="col-form-label">Title:</label>
                                <input type="text" class="form-control" id="task-group-title" placeholder="Enter task group title..." required>
                            </div>
                        </div>
                        <div id="brand-btn" class="modal-footer">
                            <button id="addGroup-{{ $project->id }}" type="button" 
                                class="add-group btn btn-primary" data-dismiss="modal">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal fade" id="editTaskGroupModal" tabindex="-1" role="dialog" aria-labelledby="editTaskGroupModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTaskGroupModalTitle">Edit Task Group</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="task-group-title-edit" class="col-form-label">Title:</label>
                                <input type="text" class="form-control" id="task-group-title-edit" placeholder="Enter task group title...">
                            </div>
                        </div>
                        <div id="brand-btn" class="modal-footer">
                            <button id="editGroup-{{ $project->id }}-" type="button" 
                                class="edit-group btn btn-primary" data-dismiss="modal">Save changes</button>
                        </div>
                    </div>
                </div>
            </div>            
        @endif
        <div class="pr-3"></div>
    </div>
@endsection