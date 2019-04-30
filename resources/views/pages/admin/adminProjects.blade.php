@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Projects')

@section('body')
    @include('partials.main-navbar', [
        'active' => 'projects',
        'auth' => 'admin'
    ])

    @include('partials.mainButton', [
        'text' => 'Create Project',
        'icon' => 'fas fa-plus-circle'
    ])

    <div id="content" class="container">
        <div class="row justify-content-center" id="search-bar">
            @include('partials.searchBar', ['page' => 'admin', 'content' => 'Projects...'])
        </div>
    </div>

    <div id="search-content" class="container py-3 mb-4">
        @foreach($projects as $project)
            @include('partials.cards.project', [
                       'project' => $project,
                       'admin' => true
                   ])
        @endforeach
    </div>
@endsection