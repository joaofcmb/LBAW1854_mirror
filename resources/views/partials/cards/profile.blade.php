
@if($isLeader)
    <div class="profile card my-3">
        <div class="card-body text-center">
            <a href="{{ route('profile', ['id' => $leader->id]) }}">
                <img src="{{ asset('img/profile.png') }}" width="125px" height="125px"
                        class="profile-img-team d-inline-block rounded-circle align-self-center my-3 my-md-1 p-md-0 p-lg-3 p-xl-0 "
                        alt="User photo">
                <p class="m-0 pt-2">{{ $leader->username }}</p>
            </a>
            <a href="" class="float-right">
                <i class="{{ $leader->follow ? 'fas' : 'far' }} fa-star"></i>
            </a>
        </div>
    </div>
@else
    <div class="profile card my-3">
        <div class="card-body p-2">
            @isset($user->id_user)
                <a href="{{ route('profile', ['id' => $user->id_user]) }}">
            @else
                <a href="{{ route('profile', ['id' => $user->id]) }}">
            @endisset
                <img src="{{ asset('img/profile.png') }}" width="50" height="50"
                        class="d-inline-block rounded-circle align-self-center my-auto"
                        alt="User photo">
                <span class="pl-4">{{ $user->username }}</span>
            </a>
            <a href="" class="float-right pt-2 pr-2">
                <i class="{{ $follow ? 'fas' : 'far' }} fa-star"></i>
            </a>
        </div>
    </div>
@endif