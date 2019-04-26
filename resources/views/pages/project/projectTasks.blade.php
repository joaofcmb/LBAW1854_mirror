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
            'ungrouped' => true
        ])

        @foreach($projectTaskGroups as $taskGroup)
            @include('partials.taskGroup', [
                'isProjectManager' => $isProjectManager,
                'title' => $taskGroup->title,
                'tasks' => $taskGroup->tasks,
                'ungrouped' => false
            ])
        @endforeach

        <div class="main-tab flex-shrink-0 card open border-left-0 border-right-0 rounded-0 py-2 mr-3 mb-auto">
            <div id="add-group" class="my-1 mx-5">
                <h4 class="m-0">Add Group <i class="fas fa-plus-circle"></i></h4>
            </div>
        </div>
        <div class="pr-3"></div>
    </div>
@endsection