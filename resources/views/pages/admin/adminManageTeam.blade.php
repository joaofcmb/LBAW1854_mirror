@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/admin-teams.css') }}" rel="stylesheet">
@endsection

@section('title')
    @isset($team)
        Edit Team
    @else
        Create Team
    @endisset
@endsection

@section('body')
    @include('partials.main-navbar', [
        'active' => 'teams',
        'auth' => 'admin'
    ])
    
    <div id="menu-option" class="container-fluid justify-content-start mx-auto py-4">
        <a href="{{ route('admin-teams') }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
    </div>

    <div id="search-content" class="container-fluid px-3">
        <div class="row">
            <div class="col-lg-6 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h4>Users</h4>
                    </div>                    
                    @include('partials.searchBar', ['page' => 'manageTeam', 'content' => 'Users...', 'searchPage' => 'manageTeam'])
                    <div id="search-display">
                        @foreach($users as $user)
                            @include('partials.cards.profile', [
                                'isLeader' => false,
                                'user' => $user,
                                'manageTeam' => true
                            ])
                        @endforeach
                    </div>                    
                </div>
            </div>
            <div class="col-lg-6 px-sm-3 px-lg-5 pb-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h4>Team</h4>
                    </div>
                    <div class="card-body">
                        <form id="manage-team-form" method="POST" 
                            @isset($team)
                                action="{{ route('admin-edit-team-action', ['id' => $team->id ]) }}"
                            @else
                                action="{{ route('admin-create-team-action') }}"
                            @endisset>
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input type="text" class="form-control" id="teamName" name="name" placeholder="Name" required
                                    @isset($team)
                                        value="{{ $team->name }}"
                                    @endisset>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="teamSkill" name="skill" placeholder="Skill"
                                    @isset($team)
                                        value="{{ $team->skill }}"
                                    @endisset>
                            </div>
                            <input type="text" hidden id="teamLeader" name="id_leader">
                            <input type="text" hidden id="teamMembers" name="members">                            
                        </form>
                        <button id="submit" type="submit" hidden form="manage-team-form"></button>
                        <div id="Leader">
                            <h5 class="text-center">Leader</h5>
                            @isset($team)
                                @include('partials.cards.profile', [
                                    'isLeader' => false,
                                    'user' => (object) array('id' => $team->id_leader, 'username' => $team->leader->username),
                                    'manager' => true,
                                    'teamMember' => false 
                                ])
                            @endisset
                        </div>
                        <div id="Members">
                            <h5 class="text-center">Members</h5>
                            @isset($team)
                                @foreach($team->members as $member)
                                    @include('partials.cards.profile', [
                                        'isLeader' => false,
                                        'user' => $member,
                                        'manager' => true,
                                        'teamMember' => true  
                                    ])
                                @endforeach
                            @endisset
                        </div>                        
                        <div id="action-button" class="text-center">
                            @isset($team)
                                <a id="manageTeam-{{ $team->id }}" class="manage-team btn mt-3" role="button" >
                                    Update
                            @else
                                <a id="manageTeam" class="manage-team btn mt-3" role="button">
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