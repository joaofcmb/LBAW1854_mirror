@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/admin-projects.css') }}" rel="stylesheet">
@endsection

@section('title')
    @isset($project)
        Edit
    @else
        Create
    @endisset
@endsection
@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => 'projects',
            'auth' => 'admin'
        ])
    </div>

    <div id="menu-option" class="container-fluid mx-auto py-4">
        <div class="row justify-content-start">
            <div class="col-sm-4 ml-2">
                <a href="{{ route('admin-projects') }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
            </div>
        </div>
    </div>

    <div id="search-content" class="container-fluid px-3">
        <div class="row">
            <div class="col-lg-12 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h5>{{ isset($project) ? 'Edit' : 'Create' }} Project</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-9">
                                    <input type="text" class="form-control" id="projectName"
                                           @isset($project)
                                                value="{{ $project->name }}"
                                           @else
                                                placeholder="Name"
                                           @endisset
                                    >
                                </div>
                                <div class="form-group col-md-2 offset-md-1">
                                    <div class="d-flex">
                                        <p class="m-auto px-2">Color</p>
                                        <input type="color" id="inputColor" class="form-control"
                                            @isset($project)
                                               value="#{{ $project->color }}"
                                            @endisset
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="projectDescription"
                                          @isset($project)
                                          @else
                                            placeholder="Project Description"
                                          @endisset
                                          rows="7">{{ isset($project) ? $project->description : '' }}</textarea>
                            </div>
                        </form>
                        <div id="search" class="col-sm-12 col-md-6 pl-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Project Manager" aria-label="Users..."
                                       aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary py-0" type="button" id="search-button">
                                        <a><i class="fa fa-search mr-1" aria-hidden="true"></i>Search</a>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @isset($project)
                            <div class="profile card my-3 col-sm-12 col-md-6 pl-0">
                                <div class="card-body py-1 px-2">
                                    <a href="{{ route('profile', ['id' => $project->id_leader]) }}">
                                        <img src="{{ asset('img/profile.png') }}" width="50" height="50"
                                             class="d-inline-block rounded-circle align-self-center my-auto"
                                             alt="User photo">
                                        <span class="pl-4">{{ $project->manager }}</span>
                                    </a>
                                    <a href="" class="float-right pt-2 pr-2">
                                        <i class="fas fa-fw fa-times text-danger"></i>
                                    </a>
                                </div>
                            </div>
                        @endisset
                        <div id="action-button" class="text-right mb-2">
                            <a href="#" class="btn mt-3" role="button">
                                @isset($project)
                                    Edit
                                @else
                                    Create
                                @endisset
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <footer class="fixed-bottom pl-2">
            COPYRIGHT © EPMA 2019
        </footer>
@endsection