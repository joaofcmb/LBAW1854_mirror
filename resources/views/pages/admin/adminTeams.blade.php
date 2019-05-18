@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Teams')

@section('body')
    @include('partials.main-navbar', [
        'active' => 'teams',
        'auth' => 'admin'
    ])

    @include('partials.mainButton', [
        'text' => 'Create Team',
        'icon' => 'fas fa-plus-circle'
    ])

    <div id="content" class="container">
        <div class="row justify-content-center" id="search-bar">
            @include('partials.searchBar', ['page' => 'admin', 'content' => 'Teams...', 'searchPage' => 'adminTeams'])
        </div>
    </div>

    <div id="search-content" class="container pt-3 pb-5">
        <div class="row justify-content-center">
            @foreach($teams as $team)
                @include('partials.cards.team', ['team' => $team])
            @endforeach
        </div>
    </div>
@endsection