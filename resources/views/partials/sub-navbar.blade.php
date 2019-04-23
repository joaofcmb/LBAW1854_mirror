<nav id="sub-menu" class="navbar navbar-expand-sm p-0 pr-3 pr-sm-1 pr-lg-3">
    <a class="navbar-brand px-3 py-2">Profile</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent2">
        @foreach($items as $item)
            @isset($item['active'])
                <a class="nav-item nav-link active mx-lg-3" href="{{$item['route']}}">{{$item['name']}}</a>
            @else
                <a class="nav-item nav-link mx-lg-3" href="{{$item['route']}}">{{$item['name']}}</a>
            @endisset
        @endforeach
    </div>
</nav>