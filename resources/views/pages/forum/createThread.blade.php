@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/create-task-thread.css') }}" rel="stylesheet">
@endsection

@section('title', $isProjectForum ? 'Project - Forum' : 'Company Forum')

@section('body')
    @if($isProjectForum)
        <div class="navbar-dark sticky-top">
    @endif

    @include('partials.main-navbar', [
        'active' => 'forum',
        'isProjectForum' => $isProjectForum,
        'auth' => 'session'
     ])

    @if($isProjectForum)
            @include('partials.sub-navbar', [
                'active' => 'forum',
                'project' => $project,
                'isProjectManager' => $isProjectManager
            ])
        </div>
    @endif

    @if($isProjectForum)
        @include('partials.backButton', [
            'route' => 'project-forum',
            'id_project' => $project->id
        ])
    @else
        @include('partials.backButton', [
            'route' => 'company-forum'
        ])  
    @endif

    <div id="search-content" class="container px-3">
        <div class="row">
            <div class="col-lg-12 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h5>Create Thread</h5>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <input type="text" class="form-control" id="projectName" placeholder="Title">
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" id="projectDescription" placeholder="Description"
                                          rows="7"></textarea>
                            </div>
                        </form>
                        <div id="action-button" class="text-center mb-2">
                            <a href="" class="btn mt-3" role="button">Create</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection