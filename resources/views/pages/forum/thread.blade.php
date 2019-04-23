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

    <div id="menu-option" class="container-fluid mx-auto py-4">
        <div class="row justify-content-start">
            <div class="col-sm-4 ml-2">
                <a href="{{ route('companyforum') }}"><i class="fas fa-chevron-circle-left mx-2"></i>Back</a>
            </div>
        </div>
    </div>

    <div id="search-content" class="container px-3 mb-5">
        <div class="row">
            <div class="col-lg-12 px-sm-3 px-lg-5">
                <div class="card my-3 p-3">
                    <h4>{{ $thread->title }}</h4>
                    <div class="row pt-2">
                        <i class="fas fa-user mx-3"></i>
                        <a href="">
                            <h6>{{ $thread->author_name }}</h6>
                        </a>
                    </div>
                    <p class="mt-2">{{ $thread->description }}<p>
                </div>
                @foreach($comments as $comment)
                    <div class="card p-3 my-3">
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <i class="far fa-user mx-3"></i>
                                    <a href="">
                                        <h6>{{ $comment->author_name }}</h6>
                                    </a>
                                </div>
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
                <div class="card p-3 my-3">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <i class="far fa-user mx-3"></i>
                                <a href="">
                                    <h6>Joseph Doe</h6>
                                </a>
                            </div>
                        </div>
                        <div class="col text-right">
                            <a href=""><i class="fas fa-pen mx-3"></i></a>
                            <a href=""><i class="fas fa-trash-alt mx-2"></i></a>
                        </div>
                    </div>
                    <p class="mt-2">Nulla facilisi. Curabitur id consequat
                        quam. Aliquam feugiat enim sit amet mattis luctus. Mauris semper id libero
                        nec laoreet. Nulla nulla ligula, gravida quis est eget, ultricies fermentum
                        lacus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur
                        eleifend lectus et risus finibus interdum. Morbi hendrerit ligula ac urna
                        eleifend, ut laoreet ante iaculis.<p>
                </div>
                <div class="card p-3 my-3">
                    <div class="row">
                        <div class="col">
                            <div class="row">
                                <i class="far fa-user mx-3"></i>
                                <a href="">
                                    <h6>Lilly Smith</h6>
                                </a>
                            </div>
                        </div>
                        <div class="col text-right">
                            <a href=""><i class="fas fa-pen mx-3"></i></a>
                            <a href=""><i class="fas fa-trash-alt mx-2"></i></a>
                        </div>
                    </div>
                    <p class="mt-2">Nulla facilisi. Curabitur id consequat
                        quam. Aliquam feugiat enim sit amet mattis luctus. Mauris semper id libero
                        nec laoreet. Nulla nulla ligula, gravida quis est eget, ultricies fermentum
                        lacus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur
                        eleifend lectus et risus finibus interdum. Morbi hendrerit ligula ac urna
                        eleifend, ut laoreet ante iaculis.<p>
                </div>
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-12 my-3">
                            <input type="text" class="form-control" id="threadName" placeholder="Add your comment ...">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <footer class="fixed-bottom p-1 pl-2">
            COPYRIGHT © EPMA 2019
        </footer>
    </body>
@endsection