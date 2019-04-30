@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
@endsection

@section('title', 'Task - Assign')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => '',
            'auth' => 'session'
        ])

        @include('partials.sub-navbar', [
            'active' => '',
            'project' => $project,
            'isProjectManager' => $isProjectManager
        ])
    </div>

    <div class="row w-100 mx-auto">
        <div class="col-lg-8 px-0">
            @include('partials.backButton', [
                'route' => 'overview',
                'id_project' => $project->id
            ])
            <div id="content" class="container py-3 mb-4">
                <div class="main-tab card open border-left-0 border-right-0 rounded-0 p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>Task List</h3>
                        <div class="d-flex justify-content-end align-items-center">
                            <span class="font-weight-light mr-2">{{ sizeof($tasks) + 1 }} Tasks</span>
                        </div>
                    </div>
                    <div class="mx-auto">
                        
                        @foreach($tasks as $task)
                            @if($task->id == $selectedTask->id)
                                @include('partials.cards.task', [
                                    'task' => $selectedTask,
                                    'isProjectManager' => $isProjectManager,
                                    'selected' => true
                                ])
                            @else
                                @include('partials.cards.task', [
                                    'task' => $task,
                                    'isProjectManager' => $isProjectManager
                                ])
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @include('partials.taskAssignment', [
            'teams' => $selectedTask->teams,
            'currentMilestone' => $currentMilestone,
            'milestones' => $milestones,
            'task' => $selectedTask
        ])

    </div>
@endsection