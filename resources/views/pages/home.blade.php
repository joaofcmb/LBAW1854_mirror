@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('title')
    <title>EPMA- Home</title>
@endsection

@section('body')
    <body class="bg-light">

    @include('main-navbar',
    ['items' =>
        [
            ['name' => 'HOME', 'route' => route('home'), 'active'],
            ['name' => 'SEARCH', 'route' => route('home')],
            ['name' => 'FORUM', 'route' => route('home')],
            ['name' => 'PROFILE', 'route' => route('home')],
        ]
     ])

    <div class="row w-100 mx-auto">
        <div class="col-lg-8 px-0">
            <div id="content" class="container py-3 mb-4">
                @include('main-tablist',
                ['tabs' =>
                    [
                        [
                            'title' => 'Project Management',
                            'info' => count($managementProjects).' Projects',
                            'content' => $managementProjects,
                            'content-type' => 'projects',
                            'collapse' => 'open'
                        ],
                        [
                            'title' => 'Team Projects',
                            'info' => count($teamProjects).' Projects',
                            'content' => $teamProjects,
                            'content-type' => 'projects',
                            'collapse' => 'closed'
                        ],
                        [
                            'title' => 'Team Tasks',
                            'info' => count($teamTasks).' Tasks',
                            'content' => $teamTasks,
                            'content-type' => 'tasks',
                            'collapse' => 'closed'
                        ]
                    ]
                ])
            </div>
        </div>
        <div id="side-forum" class="col-lg-4 px-0 mt-md-5 mt-lg-3 mb-4">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="company-forum.html" id="companyForum">
                        <h3 class="m-0">COMPANY FORUM</h3>
                    </a>
                    <a href="create-thread.html"><i class="fas fa-plus-circle"></i></a>
                </div>

                <a href="thread.html">
                    <section class="card sticky p-2 my-3">
                        <div class="d-flex justify-content-between align-items-top">
                            <h5>New Employee Guide</h5>
                            <div class="notification"><i class="far fa-envelope mx-2"></i>2</div>
                        </div>
                        Chefao
                    </section>
                </a>

                <a href="#">
                    <section class="card sticky p-2 my-3">
                        <div class="d-flex justify-content-between align-items-top">
                            <h5>Code of Conduct</h5>
                        </div>
                        Chefao
                    </section>
                </a>

                <a href="#">
                    <section class="card p-2 my-3">
                        <div class="d-flex justify-content-between align-items-top">
                            <h5>New snack policy feedback</h5>
                            <div class="notification"><i class="far fa-envelope mx-2"></i>5</div>
                        </div>
                        John Doe
                    </section>
                </a>
            </div>
        </div>
    </div>

    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
    </body>
@endsection