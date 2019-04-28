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
    
    @include('partials.backButton', [
        'admin' => true
    ])

    <div id="search-content" class="container-fluid px-3">
        <div class="row">
            <div class="col-lg-6 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h4>Users</h4>
                    </div>
                    <div class="card-body">
                        <div id="search" class="">
                            <div class="input-group">
                                <input type="text" class="form-control p-2" placeholder="Users ..."
                                       aria-label="Users..." aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary py-0" type="button" id="search-button">
                                        <a href=""> <i class="fa fa-search mr-1" aria-hidden="true"></i>Search</a>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @foreach($users as $user)
                            @include('partials.cards.profile', [
                                'isLeader' => false,
                                'user' => $user,
                                'team' => true    
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-6 px-sm-3 px-lg-5">
                <div class="card my-2">
                    <div class="card-header">
                        <h4>Team</h4>
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" id="teamName" 
                                    @isset($team)
                                        value="{{ $team->name }}"
                                    @else
                                        placeholder="Name"
                                    @endisset>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="teamSkill"
                                    @isset($team->skill)
                                        value="{{ $team->skill }}"
                                    @else
                                        placeholder="Skill"
                                    @endif
                                >
                            </div>
                        </form>
                        <div>
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
                        <div>
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
                            <a href="" class="btn mt-3" role="button">
                                @isset($team)
                                    Update
                                @else
                                    Create
                                @endisset</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection