<div class="col-sm-4 my-3">
    <div class="card text-center">
        <div class="card-header" style="clear: both;">
            <p id="team-name" class="m-0" style="float: left;">{{ $team->name }}</p>
            <p class="m-0" style="float: right;">{{ $team->skill == null ? '' : $team->skill }}</p>
        </div>
        <div class="card-body">
            <a href="{{ route('profile', ['id' => $team->leader->id]) }}">
                <p style="font-weight: bold;">{{ $team->leader->username }}</p>
            </a>
            <div class="mt-3">
            @foreach($team->members as $member)
                <a href="{{ route('profile', ['id' => $member->id]) }}">
                    <p>{{ $member->username }}</p>
                </a>
            @endforeach
            </div>
            <a id="edit-button" href="{{ route('admin-edit-team', ['id' => $team->id]) }}" class="btn mt-3" role="button">Edit</a>
            <a id="edit-button" href="" class="btn mt-3" role="button">Remove</a>
        </div>
    </div>
</div>