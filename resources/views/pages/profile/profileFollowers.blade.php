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
            'active' => 'followers',
            'owner' => $ownUser,
        ])
    </div>
    <div id="main-content" class="row mx-auto align-items-center">
        <div class="col-lg-8 px-0 order-12 order-lg-1">
            <div id="content" class="container pb-3 p-lg-5 mb-5 mb-lg-4 align-self-center justify-content-center">
                <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
                    <h4>Followers</h4>
                    <div class="container px-5">
                        @foreach($followers as $follow)
                            @include('partials.cards.profile', [
                                'isLeader' => false,
                                'user' => $follow,
                                'follow' => $follow->followBack
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @include('partials.side-profile', [
            'user' => $user
        ])
    </div>
@endsection