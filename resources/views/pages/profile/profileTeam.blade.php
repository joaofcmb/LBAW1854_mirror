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
            'owner' => $ownUser,
            'notAdmin' => !\App\User::find($id)->isAdmin()
        ])
    </div>
    <div id="main-content" class="row mx-auto align-items-center">
        <div class="col-lg-8 px-0 order-12 order-lg-1">
            <div id="content" class="container pb-3 p-lg-5 mb-5 mb-lg-4 align-self-center justify-content-center">
                <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
                    <div class="row">
                        <div class="col-6">
                            <h4>Team - <span>{{ $team->name }}</span> </h4>
                        </div>
                        @if($team->skill != null)
                            <div class="col-6 text-right">
                                Skill: {{ $team->skill }}
                            </div>
                        @endif
                    </div>
                    <div class="container px-5">
                        <div class="row mt-3 justify-content-center">
                            <div class="col-12 col-sm-8 col-lg-5">
                                <h5 class="text-center">Leader</h5>
                                    @include('partials.cards.profile', [
                                        'leader' => $leader,
                                        'isLeader' => true
                                    ])
                            </div>
                            <div class="col-12 col-lg-7">
                                <h5 class="text-center">Members</h5>
                                @foreach($members as $member)
                                    @if($member->id_user != $id)
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
            </div>
        </div>

        @include('partials.side-profile', [
            'user' => $user
        ])
    </div>
@endsection