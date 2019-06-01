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
    @if ($errors->any())    
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>    
        </div>
    @endif
    
    <div id="menu-option" class="container-fluid justify-content-start mx-auto py-4">
        <a href="{{ route('project-tasks', ['id_project' => $project->id]) }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
    </div>

    <div id="search-content" class="container px-3">
        <div class="row">
            <div class="col-lg-12 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h5>Create Task</h5>
                    </div>
                    <div class="card-body">
                        <form id="create-task-form" method="post" 
                            action="{{ route('task-create-action', ['id_project' => $project->id , 'id_taskgroup' => $id_taskgroup]) }}">
                            {{ csrf_field() }}
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <input type="text" class="form-control" id="projectName" name="name" placeholder="Name" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="projectDescription" name="description" placeholder="Task Description" 
                                          rows="7" required></textarea>
                            </div>
                        </form>
                        <div id="action-button" class="text-center mb-2">
                            <button class="px-0 mr-2 mt-3" form="create-task-form" type="submit">
                                <a class="btn" role="button">Create & Assign</a>
                            </button>                            
                            <button class="px-0 mt-3" form="create-task-form" type="submit">
                                <a class="btn">Create</a>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection