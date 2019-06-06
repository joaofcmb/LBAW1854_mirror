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
            'active' => $type,
            'owner' => $ownUser,
        ])
    </div>
    @if ($errors->any())    
        <div class="alert alert-danger">
            <ul class="mb-0"  style="font-family: 'Comfortaa', sans-serif;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>    
        </div>
    @endif
    <div id="main-content" class="row mx-auto align-items-center">
        <div class="col-lg-8 px-0 order-12 order-lg-1">
            <div id="content" class="container py-5 align-self-center justify-content-center">
                @if(count($follow) == 0)
                    <div id="error-text" class="text-center">
                        <p>No <span>content</span> available!</p>
                    </div>
                @else
                    <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
                        @if ($type == 'followers')
                            <h4>Followers</h4>
                        @elseif ($type == 'following')
                            <h4>Following</h4>
                        @endif
                        <div class="container px-1 px-sm-5">
                            @foreach($follow as $f)
                                @include('partials.cards.profile', [
                                    'isLeader' => false,
                                    'user' => $f,
                                    'follow' => $f->followBack
                                ])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        @include('partials.side-profile', [
            'user' => $user
        ])
    </div>
@endsection