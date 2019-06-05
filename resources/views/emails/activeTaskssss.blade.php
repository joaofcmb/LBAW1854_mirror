@extends('layouts.app')

@section('css')
    <link href="{{ asset('css/home.css') }}" rel="stylesheet">
@endsection

@section('title', 'Home')

@section('body')
<div class="row w-100 mx-auto">
        <div class="col-lg-8 px-0">
            <div id="content" class="container py-3 mb-4">
                @include('partials.collapsableTablist', [
                    'onProject' => false,
                    'tabs' =>
                        [
                            [
                                'title' => 'Team Tasks',
                                'info' => count($teamTasks).' Tasks',
                                'content' => $teamTasks,
                                'contentType' => 'task',
                                'isProjectManager' => false,
                                'open' => true
                            ]
                        ]
                ])
            </div>
        </div>
</div>
@endsection