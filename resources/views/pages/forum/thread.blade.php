@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/thread.css') }}" rel="stylesheet">
@endsection

@section('title', 'Company Forum')

@section('body')
<body class="bg-light">
    @include('partials.main-navbar', [
        'items' =>
            [
                ['name' => 'HOME', 'route' => route('home')],
                ['name' => 'SEARCH', 'route' => ''],
                ['name' => 'FORUM', 'route' => route('companyforum'), 'active' => true],
                ['name' => 'PROFILE', 'route' => ''],
            ],
        'auth' => 'session'
     ])

    <div id="menu-option" class="justify-content-start container py-4">
        <a href="{{ route('companyforum') }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
    </div>
    <div id="thread-content" class="container px-3 mb-5">
        <div class="card my-3 p-3">
            <h4>{{ $thread->title }}</h4>
            <a class="d-flex flex-row pt-1"href="">
                <i class="fas fa-user mr-1"></i>
                <h6>{{ $thread->author_name }}</h6>
            </a>
            <p class="mt-2">{{ $thread->description }}<p>
        </div>
        @foreach($comments as $comment)
            <div class="card p-3 my-3">
                <div class="row">
                    <div class="col">
                        <a class="d-flex flex-row pt-1"href="">
                            <i class="fas fa-user mr-1"></i>
                            <h6>{{ $comment->author_name }}</h6>
                        </a>
                    </div>
                    <div class="col text-right">
                        @if($comment->id_author === Auth::user()->getAuthIdentifier())
                            <a href=""><i class="fas fa-pen mx-3"></i></a>
                        @endif
                        @if($comment->id_author === Auth::user()->getAuthIdentifier() || Auth::user()->isAdmin())
                            <a href=""><i class="fas fa-trash-alt mx-2"></i></a>
                        @endif
                    </div>
                </div>
                <p class="mt-2">{{ $comment->text }}<p>
            </div>
        @endforeach
        <form>
            <div class="form-row">
                <div class="form-group col-md-12 my-3">
                    <input type="text" class="form-control" id="threadName" placeholder="Add your comment ...">
                </div>
            </div>
        </form>
    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
</body>
@endsection