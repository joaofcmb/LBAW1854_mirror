@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('title', 'Home')

@section('body')
    <body class="bg-light">

        @include('partials.main-navbar',
        ['items' =>
            [
                ['name' => 'HOME', 'route' => route('home'), 'active' => true],
                ['name' => 'SEARCH', 'route' => ''],
                ['name' => 'FORUM', 'route' => route('companyforum')],
                ['name' => 'PROFILE', 'route' => ''],
            ]
         ])

        <div class="row w-100 mx-auto">
            <div class="col-lg-8 px-0">
                <div id="content" class="container py-3 mb-4">
                    @include('partials.collapsable-tablist',
                    ['tabs' =>
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
                            ],
                            [
                                'title' => 'Team Tasks',
                                'info' => count($teamTasks).' Tasks',
                                'content' => $teamTasks,
                                'contentType' => 'task',
                            ]
                        ]
                    ])
                </div>
            </div>
            <div id="side-forum" class="col-lg-4 px-0 mt-md-5 mt-lg-3 mb-4">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('companyforum') }}" id="companyForum">
                            <h3 class="m-0">COMPANY FORUM</h3>
                        </a>
                        <a href="create-thread.html"><i class="fas fa-plus-circle"></i></a>
                    </div>
                    @foreach($threads as $thread)
                        <a href="{{ route('companyforumthread', ['id_thread' => $thread->id]) }}">
                            <section class="card sticky p-2 my-3">
                                <div class="d-flex justify-content-between align-items-top">
                                    <h5>{{ $thread->title }}</h5>
                                    <!-- <div class="notification"><i class="far fa-envelope mx-2"></i>2</div> -->
                                </div>
                                {{ $thread->author_name }}
                            </section>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <footer class="fixed-bottom p-1 pl-2">
            COPYRIGHT © EPMA 2019
        </footer>
    </body>
@endsection