@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
@endsection

@section('title', 'Project - Roadmap')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => '',
            'auth' => 'session'
        ])

        @include('partials.sub-navbar', [
            'active' => 'roadmap',
            'project' => $project,
            'isProjectManager' => $isProjectManager
        ])
    </div>

    <div class="row w-100 mx-auto">
        @if($isProjectManager)
            @include('partials.mainButton', [
                'text' => 'Create Milestone',
                'icon' => 'fas fa-plus-circle',
                'modal' => 'data-toggle=modal data-target=#createMilestoneModal'
            ])

            <div class="modal fade" id="createMilestoneModal" tabindex="-1" role="dialog" aria-labelledby="createMilestoneModalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="createMilestoneModalTitle">Create Milestone</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="create-milestone-form" method="post" action="{{ route('create-milestone-action', ['id_project' => $project->id]) }}">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="milestone-name" class="col-form-label">Name:</label>
                                    <input type="text" class="form-control" name="name" id="milestone-name" placeholder="Enter milestone name..." required>
                                </div>
                                <div class="form-group">
                                    <label for="milestone-deadline" class="col-form-label">Deadline:</label>
                                    <input type="date" class="form-control" name="deadline" id="milestone-deadline" min="{{$date}}" required>
                                </div>
                            </form>
                        </div>
                        <div id="brand-btn" class="modal-footer">
                            <button form="create-milestone-form" class="btn btn-primary" type="submit">Create</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div id="content" class="container py-3 mb-4">     

            @include('partials.roadmap', [
                'page' => 'roadmap',
                'milestones' => $milestones,
                'date' => $date,
                'currentMilestone' => $currentMilestone
            ])
            
            @if ($currentMilestone != null)
                <div id="milestone{{ $currentMilestone->id }}" data-parent="#content" class="collapse show main-tab card border-left-0 border-right-0 rounded-0 p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3>{{ $currentMilestone->name }}
                            @if($isProjectManager)
                                <button type="button" data-toggle="modal" data-target="#editMilestoneModal">
                                    <i class="far fa-edit ml-2"></i>
                                </button>
                                <i id="removeMilestone-{{ $project->id }}-{{ $currentMilestone->id }}" class="remove-milestone far fa-trash-alt"></i>
                            @endif                            
                        </h3>                       
                        <span class="font-weight-light mr-2 flex-shrink-0">{{ sizeof($currentMilestone->tasks) }} remaining</span>
                    </div>                   
                    <div class="mx-auto">
                        @foreach($currentMilestone->tasks as $task)
                            @include('partials.cards.task', [
                                'task' => $task,
                                'isProjectManager' => $isProjectManager
                            ])
                        @endforeach
                    </div>
                </div>

                @if($isProjectManager)
                    <!-- Modal -->
                    <div class="modal fade" id="editMilestoneModal" tabindex="-1" role="dialog" aria-labelledby="editMilestoneModalTitle" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editMilestoneModalTitle">Edit Milestone</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="milestone-name" class="col-form-label">Name:</label>
                                        <input type="text" class="form-control" id="milestone-name" value="{{ $currentMilestone->name }}">

                                        <label for="milestone-deadline" class="col-form-label">Deadline:</label>
                                        <input type="date" class="form-control" id="milestone-deadline" min="{{$date}}" 
                                            value="{{ \Carbon\Carbon::parse($currentMilestone->deadline)->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div id="brand-btn" class="modal-footer">
                                    <button id="editMilestone-{{ $project->id }}-{{ $currentMilestone->id }}" type="button" 
                                        class="update-milestone btn btn-primary" data-dismiss="modal">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection