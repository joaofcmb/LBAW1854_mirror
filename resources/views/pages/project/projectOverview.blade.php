@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
@endsection

@section('title', 'Project - Overview')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => '',
            'auth' => 'session'
        ])

        @include('partials.sub-navbar', [
            'active' => 'overview',
            'project' => $project,
            'isProjectManager' => $isProjectManager
        ])
    </div>
    <div class="row w-100 mx-auto">
        <div class="col-lg-8 px-0">
            <div id="content" class="container py-3 mb-4">
                @include('partials.roadmap', [
                    'page' => 'overview',
                    'milestones' => $milestones,
                    'date' => $date,
                    'currentMilestone' => $currentMilestone
                ])

                @include('partials.collapsableTablist', [
                    'onProject' => true,
                    'tabs' =>
                        [
                            [
                                'title' => 'Todo Tasks',
                                'info' => count($project->tasks_todo).' Tasks',
                                'content' => $project->tasks_todo,
                                'contentType' => 'task',
                                'isProjectManager' => $isProjectManager
                            ],
                            [
                                'title' => 'Ongoing Tasks',
                                'info' => count($project->tasks_ongoing).' Tasks',
                                'content' => $project->tasks_ongoing,
                                'contentType' => 'task',
                                'isProjectManager' => $isProjectManager,
                                'open' => true
                            ],
                            [
                                'title' => 'Done Tasks',
                                'info' => count($project->tasks_done).' Tasks',
                                'content' => $project->tasks_done,
                                'contentType' => 'task',
                                'isProjectManager' => $isProjectManager
                            ],
                        ]                    
                ])
            </div>
        </div>

        @include('partials.sideforum', [
            'threads' => $threads,
            'id_project' => $project->id
        ])
    </div>
@endsection