@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection

@section('title', 'Profile')

@section('body')
    <body class="bg-light">
    @include('partials.main-navbar', [
        'items' =>
            [
                ['name' => 'HOME', 'route' => route('home'), ],
                ['name' => 'SEARCH', 'route' => route('search')],
                ['name' => 'FORUM', 'route' => route('companyforum')],
                ['name' => 'PROFILE', 'route' => route('profile', ['id' => Auth::user()->getAuthIdentifier()]), 'active' => true]
            ],
        'auth' => 'session'
    ])
    @include('partials.sub-navbar', [
        'items' =>
            [
                ['name' => 'Information', 'route' => route('profile', ['id' => $id]), 'toPrint' => $ownUser],
                ['name' => 'Team', 'route' => route('profile-team', ['id' => $id]), 'toPrint' => !\App\User::find($id)->isAdmin()],
                ['name' => 'Favorite Projects', 'route' => route('profile-favorites', ['id' => $id]), 'active' => true],
                ['name' => 'Followers', 'route' => route('profile-followers', ['id' => $id])],
                ['name' => 'Following', 'route' => route('profile-following', ['id' => $id])]
            ]
    ])
    <div id="main-content" class="row mx-auto align-items-center">
        <div class="col-lg-8 px-0 order-12 order-lg-1">
            <div id="content" class="container p-lg-5 align-self-center justify-content-center">
                <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
                    <h4>Favorite Projects</h4>
                    <div class="container">
                        @foreach($favorites as $favorite)
                            <div id="project" class="card py-2 px-3 mt-4 mx-3 mx-sm-5" style="border-top-width: 0.25em; border-top-color: {{ $favorite->color }};">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ $favorite->lock ? route('project-overview', ['id' => $favorite->id]) : '' }}">
                                        <h5 class="card-title mb-3 ">{{ $favorite->name }}</h5>
                                    </a>
                                    <h5>
                                        <a href=""><i class="{{ $favorite->favorite ? 'fas' : 'far' }} fa-star" aria-hidden="true"></i></a>
                                        <a href=""><i class="fa fa-{{ $favorite->lock ? 'unlock' : 'lock' }}" aria-hidden="true"></i></a>
                                    </h5>
                                </div>
                                <div class="row">
                                    <div class="col-sm-7">
                                        Project Manager:
                                        <a href="{{ route('profile', ['id' => $favorite->id_manager]) }}">
                                            <h6 class="d-inline-block mb-3">{{ $favorite->manager }}</h6>
                                        </a>
                                        <br>
                                        Brief Description:
                                        <h6 class="d-inline">{{ $favorite->description }}</h6>
                                    </div>
                                    <div class="col-sm-5 mt-3 mt-sm-0">
                                        Statistics
                                        <h6>
                                            <p class="m-0"><i class="far fa-fw fa-user mr-1"></i>{{ $favorite->teams }} Teams involved</p>
                                            <p class="m-0"><i class="fas fa-fw fa-check text-success mr-1"></i>{{ $favorite->num_tasks_done }} Tasks
                                                concluded</p>
                                            <p class="m-0"><i class="fas fa-fw fa-times text-danger mr-1"></i>{{ $favorite->num_tasks_todo }} Tasks
                                                remaining</p>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div id="side-forum" class="col-lg-4 px-0 my-5 order-1 order-lg-12">
            <div class="container pb-4">
                <div class="row justify-content-center">
                    <div id="profile-picture" class="col-  mx-auto">
                        <img class="profile-img rounded-circle" src="{{ asset('img/avatar.png') }}" width="250" height="250"
                             alt="User Photo">
                        <div id="change-picture" class="text-center">
                            <i class="fas fa-camera"></i>
                            <p>Update</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center pt-4 pb-3">
                    <div id="name-container" class=" col-8 col-md-5 align-self-center text-center">
                        <h5>{{ $user->username }}</h5>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div id="biography" class="col-10 col-md-8 pt-3">
                        <h5>Biography</h5>
                        <p class="pt-2">{{ $user->biography }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
    </body>
@endsection