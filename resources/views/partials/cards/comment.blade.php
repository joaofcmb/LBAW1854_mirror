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
    <div class="card pb-0 px-3 pt-3 my-3">
        <div class="row">
            <div class="col">
                <a class="d-flex flex-row pt-1" href="{{ route('profile', ['id' => $comment->id_author]) }}">
                    <i class="fas fa-user mr-1"></i>
                    <h6>{{ $comment->author_name }}{{ $comment->username }}</h6>
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
        <p class="mt-2">{{ $comment->text }}</p>
    </div>
@endisset