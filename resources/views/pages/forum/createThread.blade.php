@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/create-task-thread.css') }}" rel="stylesheet">
@endsection

@section('title', $isProjectForum ? 'Project - Forum' : 'Company Forum')

@section('body')
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
    @endif

    <div id="menu-option" class="container-fluid mx-auto py-4">
        <div class="row justify-content-start">
            <div class="col-sm-4 ml-2">
                <a href="{{ $isProjectForum ? route('project-forum', ['id' => $project->id]) : route('companyforum') }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
            </div>
        </div>
    </div>

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

        <footer class="fixed-bottom p-1 pl-2">
            COPYRIGHT © EPMA 2019
        </footer>
@endsection