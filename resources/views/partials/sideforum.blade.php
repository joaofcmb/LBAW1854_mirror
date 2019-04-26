@isset($id_project)
    <div id="side-forum" class="col-lg-4 px-0 mt-md-5 mt-lg-3 mb-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('project-forum', ['id' => $id_project]) }}" style="text-decoration: none; color: black;">
                    <h3 class="m-0">PROJECT FORUM</h3>
                </a>
                <a href="{{ route('forum-create-thread', ['id' => $id_project]) }}">
                    @if($canCreateThread)
                        <i class="fas fa-plus-circle"></i>
                    @endif
                </a>
            </div>
            @foreach($threads as $thread)
                <a href="{{ route('forum-thread', ['id_project' => $id_project, 'id_thread' => $thread->id]) }}" style="color: black; text-decoration: none;">
                    <section class="card border-hover sticky p-2 my-3">
                        <div class="d-flex justify-content-between align-items-top">
                            <h5>{{ $thread->title }}</h5>
                        </div>
                        {{ $thread->author_name }}
                    </section>
                </a>
            @endforeach
        </div>
    </div>
@else
    <div id="side-forum" class="col-lg-4 px-0 mt-md-5 mt-lg-3 mb-4">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('companyforum') }}" id="companyForum">
                    <h3 class="m-0">COMPANY FORUM</h3>
                </a>
                <a href="{{ route('company-forum-create-thread') }}"><i class="fas fa-plus-circle"></i></a>
            </div>
            @foreach($threads as $thread)
                <a href="{{ route('companyforum-thread', ['id_thread' => $thread->id]) }}">
                    <section class="card sticky p-2 my-4">
                        <div class="d-flex justify-content-between align-items-top">
                            <h5>{{ $thread->title }}</h5>
                        </div>
                        {{ $thread->author_name }}
                    </section>
                </a>
            @endforeach
        </div>
    </div>
@endisset
    