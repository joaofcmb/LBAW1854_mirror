<div id="side-profile" class="col-lg-4 px-0 mt-4 mb-4 order-1 order-lg-12 d-flex align-items-center">
    <div class="container pb-4 my-auto">
        <div class="row justify-content-center">
            <div id="profile-picture" class="col-  mx-auto">
                <img @isset ($isInfo) id="profile-img" @endisset class="profile-img rounded-circle"
                     src="{{ file_exists('img/profile/' . $user->id . '.png') ? asset('img/profile/' . $user->id . '.png') :
                        (file_exists('img/profile/' . $user->id . '.jpg') ? asset('img/profile/' . $user->id . '.jpg') : asset('img/profile.png')) }}" width="250" height="250"
                     alt="User photo"> 
                @isset ($isInfo)
                    <div id="upload-profile-picture" class="text-center">
                        <i class="fas fa-camera"></i>
                        <p>Update</p>
                    </div>
                    <form id="upload-profile-picture-form" method="post" action="{{ route('upload-picture') }}" enctype="multipart/form-data" style="display: none;">
                        {{ csrf_field() }}

                        <input type="hidden" name="type" value="profile">
                        <input type="file" name="picture" class="form-control-file" id="upload-profile-picture-file">
                        <input id="upload-profile-picture-form-submit" type="submit">
                    </form>
                @endisset
            </div>
        </div>
        @isset ($isInfo)
            @if ($errors->any())
                <div class="row justify-content-center mt-3">
                @foreach ($errors->all() as $error)
                    <span class="error" style="color: red; font-family: 'Comfortaa', sans-serif;">
                        {{ $error }}
                    </span>
                @endforeach
                </div>
            @endif
        @endisset
        <div class="row justify-content-center pt-4 pb-3">
            <div id="name-container" class=" col-8 col-md-5 align-self-center text-center">
                <h5>{{ $user->first_name . " " . $user->last_name }}</h5>
            </div>
        </div>
        <div class="row justify-content-center mx-3 pt-3">
            <div id="biography" class="p-3">
                <h5>Biography 
                    @isset($isInfo)
                        <i id="edit-biography" class="fas fa-edit ml-2 float-right"></i>
                    @endisset 
                </h5>
                <p id="{{ $user->id }}" class="pt-2 mb-0">{{ $user->biography }}</p>
            </div>
        </div>
    </div>
</div>

@isset ($isInfo)
    <div id="background-button" class="row col-1 p-0">
        <div class="col-12 text-right p-0">
            <a class="btn px-2 py-1" role="button">
                <i class="fa fa-camera" aria-hidden="true"></i>
            </a>
        </div>
    </div>

    <form id="upload-profile-background-form" method="post" action="{{ route('upload-picture') }}" enctype="multipart/form-data" style="display: none;">
        {{ csrf_field() }}

        <input type="hidden" name="type" value="background">
        <input type="file" name="picture" class="form-control-file" id="upload-profile-background-file">
        <input id="upload-profile-background-form-submit" type="submit">
    </form>
@endisset