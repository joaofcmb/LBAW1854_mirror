@isset ($taskComment)
    <section class="card float-sm-left p-2 m-2 mt-3">
        <div class="d-flex justify-content-between" id="comment-header">
            <h6 class="mb-2"><a href="{{ route('profile', ['id' => $comment->id_author]) }}"
                ><i class="fa fa-user" aria-hidden="true"></i> {{ $comment->username }}</a></h6>

            <h6 id="discussion-icons">
                @if(Auth::user()->getAuthIdentifier() == $comment->id_author)
                    <a href=""><i class="far fa-edit"></i></a>
                @endif
                @if($comment->isTeamLeader || Auth::user()->getAuthIdentifier() == $comment->id_author)
                    <a href=""><i class="far fa-trash-alt"></i></a>
                @endif
            </h6>
        </div>
        <p class="mb-1">{{ $comment->text }}</p>
    </section>
@else
    <div id="comment-{{ $comment->id }}" class="card pb-0 px-3 pt-3 my-3">
        <div class="row">
            <div class="col">
                <a class="d-flex flex-row pt-1" href="{{ route('profile', ['id' => $comment->id_author]) }}">
                    <i class="fas fa-user mr-1"></i>
                    <h6>{{ $comment->author_name }}</h6>
                </a>
            </div>
            <div class="col text-right">
            @if(($isProjectForum && ($project->id_manager == Auth::user()->getAuthIdentifier() || Auth::user()->getAuthIdentifier() === $thread->id_author ))
                || (!$isProjectForum && ($comment->id_author === Auth::user()->getAuthIdentifier() || Auth::user()->isAdmin())))
                <a style="cursor: pointer;"><i id="comment-edit-{{ $comment->id }}-{{ $thread->id }}-{{ $isProjectForum ? $project->id : 0 }}" 
                    {{ $isProjectForum ? "belongsToProject=\"true\"" : '' }} class="comment-edit fas fa-pen mx-2"></i></a>
                <a style="cursor: pointer;"><i id="comment-{{ $comment->id }}-{{ $thread->id }}-{{ $isProjectForum ? $project->id : 0 }}" 
                    {{ $isProjectForum ? "belongsToProject=\"true\"" : '' }} class="comment-delete fas fa-trash-alt mx-2"></i></a>
            @endif
            </div>
        </div>
        <p class="mt-2">{{ $comment->text }}</p>
    </div>
@endisset