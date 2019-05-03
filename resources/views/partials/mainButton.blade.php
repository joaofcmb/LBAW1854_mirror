<div id="create" class="container-fluid mx-auto mt-4">
    <div class="row justify-content-center">
        <div class="col-sm- py-2 px-3">
            @if ($text == 'Create Team')
                <a href="{{ route('admin-create-team') }}">
            @elseif ($text == 'Create Project')
                <a href="{{ route('admin-create-project') }}">
            @elseif ($text == 'Create Milestone')
                <a href="">
            @endif
                <span>{{$text}}</span>
                @isset ($icon)
                    <i class="{{$icon}}"></i>
                @endisset
            </a>
        </div>
    </div>
</div>
