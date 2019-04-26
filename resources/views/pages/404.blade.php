@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/error-page.css') }}" rel="stylesheet">
@endsection

@section('title', '404')

@section('body')
    <body>
    <div class="navbar-dark sticky-top">
        <nav class="navbar navbar-expand-sm py-0 px-3 px-sm-1 px-lg-3">
            <a class="navbar-brand" href="">
                <img src="{{ asset('img/logo.png') }}" width="35" class="d-inline-block" alt="Website Logo">
                <span class="font-weight-bold font-italic">EPMA</span>
            </a>
        </nav>
    </div>
    <div class="row m-0 align-items-center justify-content-center text-center">
        <div id="error-text" class="col-12 col-lg-5 py-5">
            <p>Error <span>404</span></p>
            <p>Page Not Found !</p>
        </div>
        <div class="col-12 col-lg-5 py-5">
            <img src="{{ asset('img/error-robot.png') }}" width="65%">
        </div>
    </div>

    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
    </body>
@endsection