<div id="side-forum" class="col-lg-4 px-0 mt-md-5 mt-lg-3 mb-4">
    <div class="container h-100">
        <h3>TASK ASSIGNMENT</h3>

        <nav class="nav nav-tabs nav-justified">
            <a class="nav-item nav-link active" data-toggle="tab" role="tab" href="#teams" aria-controls="teams"
                aria-selected="true">Teams</a>
            <a class="nav-item nav-link" data-toggle="tab" role="tab" href="#milestone"
                aria-controls="milestone" aria-selected="false">Milestone</a>
        </nav>
        <div class="tab-content border border-top-0">
            <div class="tab-pane fade show active" id="teams" role="tabpanel">
                @include('partials.searchBar', ['page' => 'assign', 'content' => 'Teams...', 'searchPage' => 'teamAssign'])

                <div id="search-content">
                    @foreach($teams as $team)
                        <div id="{{ $team->id }}" class="card open flex-row justify-content-between p-2 mx-3 my-2">
                            <div class="custom-control custom-checkbox">
                                <input checked type="checkbox" class="custom-control-input" id="team-{{ $team->id }}">
                                <label class="custom-control-label team-name" for="team-{{ $team->id }}">{{ $team->name }}</label>
                            </div>
                            {{ $team->skill == null ? '' : $team->skill }}
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="tab-pane fade" id="milestone" role="tabpanel">
                <div class="card flex-row justify-content-between p-2 mx-3 my-2">
                    <div class="custom-control custom-radio">
                        <input checked type="radio" class="custom-control-input" name="milestone" id="milestone{{ $currentMilestone->id }}">
                        <label class="custom-control-label team-name" for="milestone{{ $currentMilestone->id }}">{{ $currentMilestone->name }}</label>
                    </div>
                </div>

                @foreach($milestones as $milestone)
                    @if($milestone->id != $currentMilestone->id)
                        <div class="card flex-row justify-content-between p-2 mx-3 my-2">
                            <div class="custom-control custom-radio">
                                <input type="radio" class="custom-control-input" name="milestone" id="milestone{{ $milestone->id }}">
                                <label class="custom-control-label team-name" for="milestone{{ $milestone->id }}">{{ $milestone->name }}</label>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="text-center">
            <div id="create" class="container-fluid mx-auto mb-2">
                <div class="row mt-4 justify-content-center">
                    <a href="#">
                        <div class="col-sm- px-3">Assign</div>
                    </a>
                </div>
            </div>
            <h6 class="py-1">{{ $task->title }}</h6>
        </div>
    </div>
</div>