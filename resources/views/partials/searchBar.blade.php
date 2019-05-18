<div class="search-bar {{ $searchPage }} @switch($page)
        @case('admin')
            col-12 col-sm-10 col-md-8 pt-4
            @break
        @case('manageTeam')
            @break
        @case('manageProject')
            col-sm-12 col-md-6 pl-0
            @break
        @case('assign')    
            p-3
            @break
        @case('search')
            col-12 col-md-8 col-lg-9 pt-3 pb-2 py-md-4
            @break
    @endswitch ">
    <div class="input-group ">
        <input type="text" class="form-control" placeholder="{{ $content }}" aria-label="{{ $content }}"
                aria-describedby="basic-addon2">
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" id="search-button">
                <a href=""> <i class="fa fa-search" aria-hidden="true"></i> Search</a>
            </button>
        </div>
    </div>
</div>