<a id="thread-{{ $thread->id }}" href="{{ $isProjectForum ?
    route('forum-thread',['id_project' => $project->id, 'id_thread' => $thread->id])
    : route('companyforum-thread', ['id_thread' => $thread->id]) }}">
    <div class="card border-hover sticky p-2 my-4">
        <div class="d-flex justify-content-between align-items-top">
            <h5>{{ $thread->title }}</h5>            
            @if($isProjectForum && ($project->id_manager == Auth::user()->getAuthIdentifier() || Auth::user()->getAuthIdentifier() === $thread->id_author ))
                <form id="thread-button">
                    <button><i id="thread-{{ $thread->id }}-{{ $project->id }}" belongsToProject="true" class="thread-delete fas fa-trash-alt mx-3"></i></button>
                </form>
            @elseif(Auth::user()->isAdmin() ||  Auth::user()->getAuthIdentifier() === $thread->id_author)
                <form id="thread-button">
                    <i id="thread-{{ $thread->id }}-0" class="thread-delete fas fa-trash-alt mx-3"></i>
                </form>
            @endif                
        </div>
        <div class="row">
            <div class="col">
                <form id="thread-button" action="{{ route('profile', ['id' => $thread->id_author]) }}" method="get">
                    <button type="submit"><i class='far fa-user mr-1'></i> {{ $thread->author_name }}</button>
                </form>
            </div>
        </div>
    </div>
</a>