@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('title', 'Home')

@section('body')
        @include('partials.main-navbar', [
            'active' => 'home',
            'auth' => 'session'
        ])
        <div class="row w-100 mx-auto">
            @if (count($managementProjects) > 0 || count($teamProjects) > 0)
                <div class="col-lg-8 px-0">
                    <div id="content" class="container py-3 mb-4">
                        @include('partials.collapsableTablist', [
                            'onProject' => false,
                            'tabs' =>
                                [
                                    [
                                        'title' => 'Project Management',
                                        'info' => count($managementProjects).' Projects',
                                        'content' => $managementProjects,
                                        'contentType' => 'project',
                                        'open' => true
                                    ],
                                    [
                                        'title' => 'Team Projects',
                                        'info' => count($teamProjects).' Projects',
                                        'content' => $teamProjects,
                                        'contentType' => 'project',
                                        'open' => true
                                    ],
                                    [
                                        'title' => 'Team Tasks',
                                        'info' => count($teamTasks).' Tasks',
                                        'content' => $teamTasks,
                                        'contentType' => 'task',
                                        'isProjectManager' => false,
                                        'open' => true
                                    ]
                                ]
                        ])
                    </div>
                </div>
            @endif
            @include('partials.sideforum', ['threads' => $threads])
        </div>
@endsection