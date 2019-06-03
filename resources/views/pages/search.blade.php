@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/search.css') }}" rel="stylesheet">
@endsection

@section('title', 'Search')

@section('body')
    @include('partials.main-navbar', [
        'active' => 'search',
        'auth' => 'session'
    ])
    <div id="content" class="container">
        <div class="row">
            @include('partials.searchBar', ['page' => 'search', 'content' => 'Users...', 'searchPage' => 'globalSearch'])
            <div id="filter" class="col-12 col-md-4 col-lg-3 text-center pt-2 pb-3 py-md-4">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn active">
                        <input type="radio" name="options" id="option1" autocomplete="off" checked>
                        <i class="fa fa-user mr-1" aria-hidden="true"></i>Users</label>
                    <label class="btn">
                        <input type="radio" name="options" id="option2" autocomplete="off">
                        <i class="fas fa-project-diagram mr-1"></i>Projects</label>
                </div>
            </div>
        </div>
        <div id="search-content" class="container pb-4 mb-4">
            <div class="row justify-content-center pb-4">
                <img id="looking-img" class="mt-5"src="{{ asset('img/lookingFor.png') }}" alt="Search Picture">
            </div>
            <div class="row justify-content-center text-center">
                <p id="question"><span>Looking</span> for something ?</p>
            </div>
        </div>
    </div>
@endsection