@isset ($adminView)
    <div class="row justify-content-center pb-4">
        <div class="col-11 col-md-8 ali">
            @if(!$user->is_active)
                <div class="restore card">
            @else
                <div class="card">
            @endif
                <div class="card-body p-2">
                    @if($user->is_active)
                        <a href="{{ route('profile', ['id' => $user->id_user]) }}">
                    @endif
                        <img src="{{ asset('img/profile.png') }}" width="50" height="50"
                                class="d-inline-block rounded-circle align-self-center my-auto" alt="User photo">
                        <span class="pl-2 pl-sm-4">{{ $user->username }}</span>
                    @if($user->is_active)
                        </a>
                    @endif
                    <a href="" class="float-right pt-2 pr-2">
                        @if(!$user->is_active)
                            <span>Restore</span>
                            <i class="fas fa-trash-restore"></i>
                        @else
                            <i class="fas fa-times"></i>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
@else
    @if($isLeader)
        <div class="profile card my-3">
            <div class="card-body text-center">
                <a href="{{ route('profile', ['id' => $leader->id]) }}">
                    <img src="{{ asset('img/profile.png') }}" width="125px" height="125px"
                            class="profile-img-team d-inline-block rounded-circle align-self-center my-3 my-md-1 p-md-0 p-lg-3 p-xl-0 "
                            alt="User photo">
                    <p class="m-0 pt-2">{{ $leader->username }}</p>
                </a>
                <a class="float-right" style="cursor: pointer;">
                    @if($leader->id != Auth::user()->getAuthIdentifier())
                        <i id="user-{{ $leader->id }}" class="follow {{ $leader->follow ? 'fas' : 'far' }} fa-star"></i>
                    @endif
                </a>
            </div>
        </div>
    @else
        @isset($manager)
            @isset($teamMember)
                <div class="profile card my-3">
            @else
                <div class="profile card my-3 col-sm-12 col-md-6 pl-0">
            @endisset
        @else
            <div class="profile card my-3">
        @endisset
            <div class="card-body p-2">
                @isset($user->id_user)
                    <a href="{{ route('profile', ['id' => $user->id_user]) }}">
                @else
                    <a href="{{ route('profile', ['id' => $user->id]) }}">
                @endisset
                    <img src="{{ asset('img/profile.png') }}" width="50" height="50"
                            class="d-inline-block rounded-circle align-self-center my-auto"
                            alt="User photo">
                    <span class="pl-2 pl-sm-4">{{ $user->username }}</span>
                </a>
                <a class="float-right pt-2 pr-2">
                    @isset($manager)
                        @isset($teamMember)
                            @if($teamMember)
                                <i class="fas fa-user-tie" style="color:grey;"></i>
                            @endif
                        @endisset
                        <i class="fas fa-fw fa-times text-danger"></i>
                    @else
                        @isset($manageTeam)
                            <i class="fas fa-plus"></i>
                        @else
                            @if($user->id != Auth::user()->getAuthIdentifier())
                                <i id="user-{{ $user->id }}" class="follow {{ $follow ? 'fas' : 'far' }} fa-star" style="cursor: pointer;"></i>
                            @endif
                        @endisset
                    @endisset
                </a>
            </div>
        </div>
    @endif
@endisset