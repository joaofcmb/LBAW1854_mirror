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
        <div class="main-tab task-group border-hover flex-shrink-0 card open border-left-0 border-right-0 rounded-0 py-2 mr-5">
            <div class="mx-auto mb-1">
                <a href=""><i class="fas fa-plus fa-fw hover-icon"></i></a>
            </div>
            <div class="d-flex flex-shrink-0 justify-content-between align-items-center my-1 mx-auto">
                <h3>Ungrouped Tasks</h3>
            </div>
            <div class="px-3 overflow-auto pr-2 pl-2 mt-1 mb-2">
                @foreach($projectUngroupedTasks as $task)
                    @include('partials.cards.task', [
                        'task' => $task,
                        'isProjectManager' => $isProjectManager
                    ])
                @endforeach
            </div>
        </div>

        @foreach($projectTaskGroups as $taskGroup)
            <div class="main-tab task-group border-hover flex-shrink-0 card open border-left-0 border-right-0 rounded-0 py-2 mr-5">
                <div class="mx-auto mb-1">
                    <a href=""><i class="fas fa-plus fa-fw hover-icon mr-2"></i></a>
                    <a href=""><i class="far fa-edit fa-fw hover-icon mx-2"></i></a>
                    <a href=""><i class="far fa-trash-alt fa-fw hover-icon ml-2"></i></a>
                </div>
                <div class="d-flex flex-shrink-0 justify-content-between align-items-center my-1 mx-auto">
                    <h3>{{ $taskGroup->title }}</h3>
                </div>
                <div class="px-3 overflow-auto pr-2 pl-2 mt-1 mb-2">
                    @foreach($taskGroup->tasks as $task)
                        @include('partials.cards.task', [
                            'task' => $task,
                            'isProjectManager' => $isProjectManager
                        ])
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="main-tab flex-shrink-0 card open border-left-0 border-right-0 rounded-0 py-2 mr-3 mb-auto">
            <div id="add-group" class="my-1 mx-5">
                <h4 class="m-0">Add Group <i class="fas fa-plus-circle"></i></h4>
            </div>
        </div>
        <div class="pr-3"></div>
    </div>
@endsection