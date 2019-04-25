<div class="card pb-0 px-3 pt-3 my-3">
    <div class="row">
        <div class="col">
            <a class="d-flex flex-row pt-1" href="{{ route('profile', ['id' => $comment->id_author]) }}">
                <i class="fas fa-user mr-1"></i>
                <h6>{{ $comment->author_name }}</h6>
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