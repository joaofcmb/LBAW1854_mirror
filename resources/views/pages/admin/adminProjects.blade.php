@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Projects')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => 'projects',
            'auth' => 'admin'
        ])
    </div>

    <div id="create" class="container-fluid mx-auto mt-4">
        <div class="row justify-content-center">
            <div class="col-sm- py-2 px-3">
                <a href="{{ route('admin-create-project') }}">
                    <span>Create Project</span>
                    <i class="fas fa-plus-circle"></i>
                </a>
            </div>
        </div>
    </div>

    <div id="content" class="container">
        <div class="row justify-content-center" id="search-bar">
            <div id="search" class="col-12 col-sm-10 col-md-8 pt-4">
                <div class="input-group ">
                    <input type="text" class="form-control" placeholder="Teams..." aria-label="Teams..."
                           aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="search-button">
                            <a><i class="fa fa-search" aria-hidden="true"></i> Search</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="search-content" class="container mt-4">
        @foreach($projects as $project)
            @include('partials.cards.project', [
                       'project' => $project,
                       'admin' => true
                   ])
        @endforeach
    </div>
    <footer class="fixed-bottom pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
@endsection