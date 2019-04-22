@extends('partials.tablist')

@section('class', isset($tab->open) ? 'open' : '')

@section(tab)
    <button class="p-0" data-toggle="collapse" data-target="#{{$loop->index}}">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Team Tasks</h3>
            <div class="collapse-element d-flex justify-content-end align-items-center">
                <span class="font-weight-light mr-2">{{$tab->info}}</span>
                <a data-toggle="collapse" href="#{{$loop->index}}"><i class="fas fa-angle-down mt-1"></i></a>
                <a class="collapsed" data-toggle="collapse" href="#TeamTasks"><i class="fas fa-angle-up mt-1"></i></a>
            </div>
        </div>
    </button>
    <div class="collapse mx-auto" id="{{$loop->index}}">
        @parent
    </div>
@endsection