@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/create-task-thread.css') }}" rel="stylesheet">
@endsection

@section('title', 'Task - Create')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => '',
            'auth' => 'session'
        ])

        @include('partials.sub-navbar', [
            'active' => '',
            'project' => $project,
            'isProjectManager' => $isProjectManager
        ])
    </div>
    
    @include('partials.backButton', [
        'route' => 'tasks',
        'id_project' => $project->id
    ])

    <div id="search-content" class="container px-3">
        <div class="row">
            <div class="col-lg-12 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h5>Create Task</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <input type="text" class="form-control" id="projectName" placeholder="Name">
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="projectDescription" placeholder="Task Description"
                                          rows="7"></textarea>
                            </div>
                        </form>
                        <div id="action-button" class="text-center mb-2">
                            <a href="" class="btn mt-3 mr-2" role="button">Create & Assign</a>
                            <a href="" class="btn mt-3" role="button">Create</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection