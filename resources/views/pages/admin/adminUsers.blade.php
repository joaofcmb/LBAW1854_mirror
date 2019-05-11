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

    @if ($errors->any())    
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>    
        </div>
    @endif
    
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