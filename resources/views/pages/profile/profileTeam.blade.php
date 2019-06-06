@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/profile.css') }}" rel="stylesheet">
@endsection

@section('title', 'Profile')

@section('body')
    <div class="navbar-dark sticky-top">
        @include('partials.main-navbar', [
            'active' => 'my profile',
            'owner' => $ownUser,
            'auth' => 'session'
        ])
        @include('partials.sub-navbar', [
            'active' => 'team',
            'owner' => $ownUser
        ])
    </div>
    @if ($errors->any())    
        <div class="alert alert-danger">
            <ul class="mb-0" style="font-family: 'Comfortaa', sans-serif;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>    
        </div>
    @endif
    <div id="main-content" class="row mx-auto align-items-center" style="background-image: 
        url({{ file_exists('img/background/' . $user->id . '.png') ? asset('img/background/' . $user->id . '.png') : 
        (file_exists('img/background/' . $user->id . '.jpg') ? asset('img/background/' . $user->id . '.jpg') : '')}});  
        background-position: center; background-repeat: no-repeat; background-size: cover;">
        <div class="col-lg-8 px-0 order-12 order-lg-1">
            <div id="content" class="container py-5 align-self-center justify-content-center">
                @isset($team)
                    <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
                        <div class="row">
                            <div class="col-6">
                                <h4><i class="d-none d-md-inline">Team - </i><span>{{ $team->name }}</span> </h4>
                            </div>
                            @if($team->skill != null)
                                <div class="col-6 text-right">
                                    <i class="d-none d-md-inline">Skill: </i>{{ $team->skill }}
                                </div>
                            @endif
                        </div>
                        <div class="container px-1 px-sm-5">
                            <div class="row mt-3 justify-content-center">
                                <div class="col-12 col-sm-8 col-lg-5">
                                    <h5 class="text-center">Leader</h5>
                                        @include('partials.cards.profile', [
                                            'leader' => $team->leader,
                                            'isLeader' => true
                                        ])
                                </div>
                                <div class="col-12 col-lg-7">
                                    <h5 class="text-center">Members</h5>
                                    @foreach($team->members as $member)
                                        @if($member->id != $id)
                                            @include('partials.cards.profile', [
                                                'isLeader' => false,
                                                'user' => $member,
                                                'follow' => $member->follow
                                            ])
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div id="error-text" class="text-center">
                        <p>No <span>content</span> available!</p>
                    </div>
                @endisset
            </div>
        </div>

        @include('partials.side-profile', [
            'user' => $user
        ])
    </div>
@endsection