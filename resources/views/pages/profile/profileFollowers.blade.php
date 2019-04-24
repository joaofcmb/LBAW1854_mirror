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
                ['name' => 'Favorite Projects', 'route' => route('profile-favorites', ['id' => $id])],
                ['name' => 'Followers', 'route' => route('profile-followers', ['id' => $id]), 'active' => true],
                ['name' => 'Following', 'route' => route('profile-following', ['id' => $id])]
            ]
    ])
    <div id="main-content" class="row mx-auto align-items-center">
        <div class="col-lg-8 px-0 order-12 order-lg-1">
            <div id="content" class="container p-lg-5 align-self-center justify-content-center">
                <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
                    <h4>Followers</h4>
                    <div class="container px-5">
                        @foreach($followers as $follow)
                            <div class="profile card my-3">
                                <div class="card-body p-2">
                                    <a href="{{ route('profile', ['id' => $follow->id]) }}">
                                        <img src="{{ asset('img/profile.png') }}" width="50" height="50"
                                             class="d-inline-block rounded-circle align-self-center my-auto"
                                             alt="User photo">
                                        <span class="pl-4">{{ $follow->username }}</span>
                                    </a>
                                    <a href="" class="float-right pt-2 pr-2">
                                        <i class="{{ $follow->followBack ? 'fas' : 'far' }} fa-star"></i>
                                    </a>
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