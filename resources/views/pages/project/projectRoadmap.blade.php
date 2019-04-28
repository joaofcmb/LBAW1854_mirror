@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
@endsection

@section('title', 'Project - Roadmap')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => '',
            'auth' => 'session'
        ])

        @include('partials.sub-navbar', [
            'active' => 'roadmap',
            'project' => $project,
            'isProjectManager' => $isProjectManager
        ])
    </div>

    <div class="row w-100 mx-auto">
         @if($isProjectManager)
                @include('partials.mainButton', [
                    'text' => 'Create Milestone',
                    'icon' => 'fas fa-plus-circle'
                ])
            @endif
        <div id="content" class="container py-3 mb-4">
           

            @include('partials.roadmap', [
                'page' => 'roadmap',
                'milestones' => $milestones,
                'date' => $date,
                'currentMilestone' => $currentMilestone
            ])

            <div id="{{ $currentMilestone->name }}" data-parent="#content" class="collapse show main-tab card border-left-0 border-right-0 rounded-0 p-2">
                <div class="d-flex justify-content-between align-items-center">
                    <h3>{{ $currentMilestone->name }}
                        @if($isProjectManager)
                            <i class="far fa-edit ml-2"></i>
                        @endif
                    </h3>
                    <span class="font-weight-light mr-2 flex-shrink-0">{{ sizeof($currentMilestone->tasks) }} remaining</span>
                </div>
                <div class="mx-auto">
                    @foreach($currentMilestone->tasks as $task)
                        @include('partials.cards.task', [
                            'task' => $task,
                            'isProjectManager' => $isProjectManager
                        ])
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection