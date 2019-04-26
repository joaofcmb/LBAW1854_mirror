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
        <div id="content" class="container py-3 mb-4">
            @if($isProjectManager)
                <div id="create" class="container-fluid mx-auto">
                    <div class="row mt-4 justify-content-center">
                        <a href="">
                            <div class="col-sm- py-2 px-3">
                                <span>Create Milestone</span>
                                <i class="fas fa-plus-circle ml-2" style="border: none;"></i>
                            </div>
                        </a>
                    </div>
                </div>
            @endif

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
                    <span class="font-weight-light mr-2 flex-shrink-0">{{ sizeof($currentMilestoneTasks) }} remaining</span>
                </div>
                <div class="mx-auto">
                    @foreach($currentMilestoneTasks as $task)
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