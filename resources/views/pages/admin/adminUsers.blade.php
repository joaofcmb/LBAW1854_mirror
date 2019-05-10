@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
@endsection

@section('title', 'Admin - Users')

@section('body')
    @include('partials.main-navbar', [
        'active' => 'users',
        'auth' => 'admin'
    ])
    
    <div id="content" class="container">
        <div class="row justify-content-center" id="search-bar">
            @include('partials.searchBar', ['page' => 'admin', 'content' => 'Users...'])
        </div>
    </div>

    <div id="search-content" class="container mt-3 mb-4">        
        @foreach($users as $user)
            @include('partials.cards.profile', [
                'adminView' => true,
                'user' => $user
            ])
        @endforeach
    </div>
@endsection