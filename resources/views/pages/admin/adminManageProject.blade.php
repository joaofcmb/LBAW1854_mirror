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

    @include('partials.backButton', [
        'route' => 'admin-projects'
    ])

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
                        @include('partials.searchBar', ['page' => 'manageProject', 'content' => 'Project Manager'])
                        
                        @isset($project)
                            @include('partials.cards.profile', [
                                'isLeader' => false,
                                'user' => (object) array('id' => $project->id_leader, 'username' => $project->manager),
                                'manager' => true
                            ])
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
    </div>
@endsection