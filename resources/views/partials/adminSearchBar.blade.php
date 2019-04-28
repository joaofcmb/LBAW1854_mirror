@isset($manageProject)
    <div id="search" class="col-sm-12 col-md-6 pl-0">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Project Manager" aria-label="Users..."
                    aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary py-0" type="button" id="search-button">
                    <a><i class="fa fa-search mr-1" aria-hidden="true"></i>Search</a>
                </button>
            </div>
        </div>
    </div>
@else
    <div id="content" class="container">
        <div class="row justify-content-center" id="search-bar">
            <div id="search" class="col-12 col-sm-10 col-md-8 pt-4">
                <div class="input-group ">
                    <input type="text" class="form-control" placeholder="{{$content}}" aria-label="{{$content}}"
                            aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="search-button">
                            <a><i class="fa fa-search" aria-hidden="true"></i> Search</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endisset