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

    @include('partials.adminSearchBar', ['content' => 'Users...'])

    <div id="search-content" class="container mt-3 mb-4">
        @foreach($users as $user)
            @include('partials.cards.profile', [
                'adminView' => true,
                'user' => $user
            ])
        @endforeach
    </div>
@endsection