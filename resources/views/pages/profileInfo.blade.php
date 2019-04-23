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
                ['name' => 'HOME', 'route' => route('home'), 'active' => true],
                ['name' => 'SEARCH', 'route' => route('search')],
                ['name' => 'FORUM', 'route' => route('companyforum')],
                ['name' => 'PROFILE', 'route' => route('profile', ['id' => Auth::user()->getAuthIdentifier()])],
            ],
        'auth' => 'session'
    ])
    <nav id="sub-menu" class="navbar navbar-expand-sm p-0 pr-3 pr-sm-1 pr-lg-3">
        <a class="navbar-brand px-3 py-2" href="">Profile</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent2">
            <a class="nav-item nav-link active mx-lg-3" href="./profile.html">Information</a>
            <a class="nav-item nav-link mx-lg-3" href="./profile-team.html">Team</a>
            <a class="nav-item nav-link mx-lg-3" href="./profile-favorites.html">Favorites</a>
            <a class="nav-item nav-link mx-lg-3" href="./profile-followers.html">Followers</a>
            <a class="nav-item nav-link mx-lg-3" href="./profile-following.html">Following</a>
        </div>
    </nav>
    <div id="main-content" class="row mx-auto align-items-center">
        <div class="col-lg-8 px-0 order-12 order-lg-1">
            <div id="content" class="container p-lg-5 align-self-center justify-content-center">
                <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
                    <h4>Information</h4>
                    <div id="edit-email" class="row justify-content-center text-center mt-2">
                        <div class="col-12">
                            <h6>Email</h6>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <input type="email" class="form-control text-center" name="email" id="email"
                                       aria-describedby="emailHelpId" placeholder="john_doe45@fe.up.pt">
                            </div>
                        </div>
                    </div>
                    <div id="edit-password" class="row justify-content-center text-center mt-2">
                        <div class="col-12">
                            <h6>Password</h6>
                        </div>
                        <div class="col-7 pt-2">
                            <div class="form-group">
                                <input type="password" class="form-control text-center mb-2" name="old-password"
                                       id="old-password" placeholder="Old Password">
                                <input type="password" class="form-control text-center mb-2" name="new-password"
                                       id="new-password" placeholder="New Password">
                                <input type="password" class="form-control text-center" name="confirm-password"
                                       id="confirm-password" placeholder="Confirm Password">
                            </div>
                        </div>
                    </div>
                    <div id="action-button" class="text-center">
                        <a href="" class="btn my-2 px-4 py-1" role="button">Save</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="side-forum" class="col-lg-4 px-0 my-5 order-1 order-lg-12">
            <div class="container pb-4">
                <div class="row justify-content-center">
                    <div id="profile-picture" class="col-  mx-auto">
                        <img class="profile-img rounded-circle" src="./IMG/avatar.png" width="250" height="250"
                             alt="User Photo">
                        <div id="change-picture" class="text-center">
                            <i class="fas fa-camera"></i>
                            <p>Update</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center pt-4 pb-3">
                    <div id="name-container" class=" col-8 col-md-5 align-self-center text-center">
                        <h5>John Doe</h5>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div id="biography" class="col-10 col-md-8 pt-3">
                        <h5>Biography<i id="edit-biography" class="fas fa-edit ml-2 float-right"></i></h5>
                        <p class="pt-2">My name is Doe, John Doe!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="background-button" class="row col-12 mt-2 mb-4 my-md-0 p-0">
        <div class="col-12 text-right p-0">
            <a href="" class="btn px-2 py-1" role="button">
                <i class="fa fa-camera" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
    </body>
@endsection