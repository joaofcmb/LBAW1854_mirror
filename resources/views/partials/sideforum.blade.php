<div id="side-forum" class="col-lg-4 px-0 mt-md-5 mt-lg-3 mb-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('companyforum') }}" id="companyForum">
                <h3 class="m-0">COMPANY FORUM</h3>
            </a>
            <a href="create-thread.html"><i class="fas fa-plus-circle"></i></a>
        </div>
        @foreach($threads as $thread)
            <a href="{{ route('companyforum-thread', ['id_thread' => $thread->id]) }}">
                <section class="card sticky p-2 my-3">
                    <div class="d-flex justify-content-between align-items-top">
                        <h5>{{ $thread->title }}</h5>
                        <!-- <div class="notification"><i class="far fa-envelope mx-2"></i>2</div> -->
                    </div>
                    {{ $thread->author_name }}
                </section>
            </a>
        @endforeach
    </div>
</div>