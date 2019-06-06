@extends('layouts.app')

@section('title', 'Help')

@section('body')
    <div class="navbar-dark sticky-top">
        <nav class="navbar navbar-expand-sm py-0 px-3 px-sm-1 px-lg-3">
            <a class="navbar-brand" href="{{ route('index') }}">
                <img src="{{ asset('img/logo.png') }}" width="35" class="d-inline-block" alt="Website Logo">
                <span class="font-weight-bold font-italic">EPMA</span>
            </a>
        </nav>
    </div>

    <div id="menu-option" class="container-fluid d-flex justify-content-between py-4">
        <a href="{{ URL::previous() }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
    </div>

    <div class="container d-flex flex-wrap flex-column align-content-center px-md-5 pb-5 mb-3" style="min-height: 80%;">
        <div id="help-text" class="text-center">
            <p>Help</p>
        </div>
        <div class="mx-md-5 px-sm-5" >
            <p><span>1. </span>A team is composed by a single Leader and Members</p>
            <p><span>1. </span>Each Member corresponds to a user on this system. A user may only be Member of one team</p>
            <p><span>2. </span>A team is identified by its name, a mandatory field, and it may be optionally attributed with a user defined skill.</p>
            <p><span>3. </span>A team must have a Leader, and this Leader must be exclusive to that team.</p>
            <p><span>4. </span>On the left hand side of the screen, a list of users without a Team is presented.</p>
            <p><span>5. </span>The search bar allows you to search for specific users to be potentially added to the Team. This search will override the list on the left hand side and may include Members of other Teams.</p>
            <p><span>5. </span>On the right hand side of the screen, the current composition of the Team is presented.</p>
            <p><span>6. </span>In order to add one user to the Team, click on <i class="fas fa-plus"></i> If that user was previously on another Team, it is removed from that Team.</p>
            <p><span>7. </span>In order to remove a Member from the Team, click on <i class="fas fa-fw fa-times text-danger"></i></p>
            <p><span>8. </span>In order to promote a Team Member to Leader (thus demoting the current Leader), click on <i class="fas fa-user-tie" style="color:grey;"></i></p>
            <p><span>9. </span>In order to save the changes and commit the presented composition of the Team, click on
                <button id="action-btn" class="btn btn-outline-secondary"><a> Update / Create </a></button></p>
        </div>
    </div>
@endsection