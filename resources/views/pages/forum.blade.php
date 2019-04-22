@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/company-forum.css') }}" rel="stylesheet">
@endsection

@section('title', 'Company Forum')

@section('body')
<body class="bg-light">
    @include('partials.main-navbar',
    ['items' =>
        [
            ['name' => 'HOME', 'route' => route('home')],
            ['name' => 'SEARCH', 'route' => ''],
            ['name' => 'FORUM', 'route' => route('companyforum'), 'active' => true],
            ['name' => 'PROFILE', 'route' => ''],
        ]
     ])

    <div class="container">
        <div id="forum-header" class="row m-3 m-md-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center my-2 pb-2">
                    <h3 class="m-0">COMPANY FORUM</h3>
                    <a href="create-thread.html"><i class="fas fa-plus-circle"></i></a>
                </div>
            </div>
        </div>

        <div class="row my-5 mx-3 mx-md-5 px-md-5">
            <div id="side-forum" class="col-12 p-5>">
                @foreach($threads as $thread)
                    <a href="thread.html">
                        <div class="card border-hover sticky p-2 my-2">
                            <div class="d-flex justify-content-between align-items-top">
                                <h5>{{ $thread->title }}</h5>
                                @php
                                    $user_id = Illuminate\Support\Facades\Auth::user()->getAuthIdentifier();

                                    if($user_id === 1 || $user_id === 2 || $user_id === $thread->id_author)
                                        echo '<i class="fas fa-trash-alt mx-3"></i>'
                                @endphp
                            </div>
                            <div class="row">
                                <div class="col">
                                    <i class="far fa-user mr-1"></i>
                                    {{ $thread->author_name }} </div>
                                <!-- <div class="notification mx-3"><i class="far fa-envelope mx-2"></i>2</div> -->
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <footer class="fixed-bottom p-1 pl-2">
        COPYRIGHT © EPMA 2019
    </footer>
</body>
@endsection