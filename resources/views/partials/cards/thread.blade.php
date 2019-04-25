<a href="{{ $isProjectForum ? 
    route('forum-thread',['id_project' => $project->id, 'id_thread' => $thread->id])
    : route('companyforum-thread', ['id_thread' => $thread->id]) }}">
    <div class="card border-hover sticky p-2 my-4">
        <div class="d-flex justify-content-between align-items-top">
            <h5>{{ $thread->title }}</h5>
                @if(Auth::user()->isAdmin() ||  Auth::user()->getAuthIdentifier() === $thread->id_author )
                    <i class="fas fa-trash-alt mx-3"></i>
                @endif
        </div>
        <div class="row">
            <div class="col">
                <i class="far fa-user mr-1"></i>
                {{ $thread->author_name }}
            </div>
        </div>
    </div>
</a>