@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/search.css') }}" rel="stylesheet">
@endsection

@section('title', 'Search')

@section('body')
    <body class="bg-light">
    @include('partials.main-navbar', [
        'items' =>
            [
                ['name' => 'HOME', 'route' => route('home')],
                ['name' => 'SEARCH', 'route' => route('search'), 'active' => true],
                ['name' => 'FORUM', 'route' => route('companyforum')],
                ['name' => 'PROFILE', 'route' => ''],
            ],
        'auth' => 'session'
    ])
    <div id="content" class="container">
        <div class="row" id="search-bar">
            <div id="search" class=" col-12 col-md-8 col-lg-9 pt-3 pb-2 py-md-4">
                <div class="input-group ">
                    <input type="text" class="form-control" placeholder="Users ..." aria-label="Users ..."
                           aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="search-button">
                            <a href=""> <i class="fa fa-search" aria-hidden="true"></i> Search</a>
                        </button>
                    </div>
                </div>
            </div>
            <div id="filter" class="col-12 col-md-4 col-lg-3 text-center pt-2 pb-3 py-md-4">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn active">
                        <input type="radio" name="options" id="option1" autocomplete="off" checked>
                        <i class="fa fa-user" aria-hidden="true"></i> Users
                    </label>
                    <label class="btn">
                        <input type="radio" name="options" id="option2" autocomplete="off">
                        <i class="fas fa-project-diagram"></i> Project
                    </label>
                </div>
            </div>
        </div>
        <div id="search-content" class="container">
            <div class="row justify-content-center pb-4">
                <img id="looking-img" class="mt-5"src="{{ asset('img/lookingFor.png') }}" alt="Search Picture">
            </div>
            <div class="row justify-content-center text-center">
                <p id="question"><span>Looking</span> for something ?</p>
            </div>
        </div>
    </div>

    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
    </body>
@endsection