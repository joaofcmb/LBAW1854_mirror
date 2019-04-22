@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/company-forum.css') }}" rel="stylesheet">
@endsection

@section('title')
    <title>EPMA- Company Forum</title>
@endsection

@section('body')
<body class="bg-light">
<nav class="navbar sticky-top navbar-expand-sm navbar-dark py-0 px-3 px-sm-1 px-lg-3">
    <a class="navbar-brand" href="">
        <img src="{{ asset('img/logo.png') }}" width="35" class="d-inline-block" alt="Website Logo">
        <span class="font-weight-bold font-italic">EPMA</span>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse py-2" id="navbarSupportedContent">
        <a class="nav-item nav-link mx-lg-3" href="{{ route('home') }}">HOME</a>
        <a class="nav-item nav-link mx-lg-3" href="search.html">SEARCH</a>
        <a class="nav-item nav-link active mx-lg-3" href="{{ route('companyforum') }}">FORUM</a>
        <a class="nav-item nav-link mx-lg-3" href="profile.html">PROFILE</a>


        <div id="authentication" class="ml-auto">
            <a href="admin-users.html">
                <img id="admin" class="profile-img d-inline-block rounded-circle my-auto"
                     src="{{ asset('img/admin.png') }}" width="50" height="50" alt="Website Logo">
            </a>
            <a href="index.html">
                <span class="font-weight-bold pl-3">Sign out</span>
            </a>
            <a href="" class="pl-lg-3"><img class="profile-img d-none d-md-inline-block rounded-circle my-auto"
                                            src="{{ asset('img/avatar.png') }}" width="50" height="50" alt="Profile Image"></a>
        </div>
    </div>
</nav>
<div class="container">
    <div id="forum-header" class="row m-3 m-md-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center my-2 pb-2">
                <h3 class="m-0">COMPANY FORUM</h3>
                <a href="create-thread.html"><i class="fas fa-plus-circle"></i></a>
            </div>
        </div>
    </div>

    <div class="row my-5 mx-3 mx-md-5 px-md-5">
        <div id="side-forum" class="col-12 p-5>">
            @foreach($threads as $thread)

                <a href="thread.html">
                    <div class="card border-hover sticky p-2 my-2">
                        <div class="d-flex justify-content-between align-items-top">
                            <h5>{{ $thread->title }}</h5>
                            @php
                                $user_id = Illuminate\Support\Facades\Auth::user()->getAuthIdentifier();

                                if($user_id === 1 || $user_id === 2 || $user_id === $thread->id_author)
                                    echo '<i class="fas fa-trash-alt mx-3"></i>'
                            @endphp
                        </div>
                        <div class="row">
                            <div class="col">
                                <i class="far fa-user mr-1"></i>
                                {{ $thread->author_name }} </div>
                            <!-- <div class="notification mx-3"><i class="far fa-envelope mx-2"></i>2</div> -->
                        </div>
                    </div>
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