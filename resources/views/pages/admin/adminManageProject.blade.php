@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/admin-projects.css') }}" rel="stylesheet">
@endsection

@section('title')
    @isset($project)
        Edit Project
    @else
        Create Project
    @endisset
@endsection

@section('body')
    @include('partials.main-navbar', [
        'active' => 'projects',
        'auth' => 'admin'
    ])

    <div id="menu-option" class="container-fluid justify-content-start mx-auto py-4">
        <a href="{{ route('admin-projects') }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
    </div>

    <div id="search-content" class="container-fluid px-3">
        <div class="row">
            <div class="col-lg-12 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h5>{{ isset($project) ? 'Edit' : 'Create' }} Project</h5>
                    </div>
                    <div class="card-body">
                        <form id="project-form" method="post" action="{{ isset($project) ? route('admin-edit-project-action', ['id' => $project->id]) : route('admin-create-project-action') }}">
                            {{ csrf_field() }}

                            <input type="hidden" id="project-manager-ID" name="projectManager" value="{{ isset($project) ? $project->id_manager : 45 }}">

                            <div class="form-row">
                                <div class="form-group col-md-9">
                                    <input type="text" class="form-control" name="name" id="projectName" placeholder="Name"
                                           @isset($project)
                                                value="{{ $project->name }}"
                                           @endisset >
                                </div>
                                <div class="form-group col-md-2 offset-md-1">
                                    <div class="d-flex">
                                        <p class="m-auto px-2">Color</p>
                                        <input type="color" id="inputColor" name="color" class="form-control"
                                            @isset($project)
                                               value="#{{ $project->color }}"
                                            @endisset
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="description" id="projectDescription" placeholder="Project Description"
                                          rows="7">{{ isset($project) ? $project->description : '' }}</textarea>
                            </div>

                            <button id="submit-project-form" hidden form="project-form" type="submit"></button>
                        </form>
                        @include('partials.searchBar', ['page' => 'manageProject', 'content' => 'Project Manager', 'searchPage' => 'manageProject'])
                        
                        <div id="search-display">
                            @isset($project)
                                @include('partials.cards.profile', [
                                    'isLeader' => false,
                                    'user' => (object) array('id' => $project->id_manager, 'username' => $project->manager->username, 'first_name' => $project->manager->first_name, 'last_name' => $project->manager->last_name),
                                    'manager' => true
                                ])
                            @endisset
                        </div>
                        <div id="action-button" class="text-right mb-2">
                            <a id="submit-manage-project-form" class="btn mt-3" role="button">@isset($project) Edit @else Create @endisset</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection