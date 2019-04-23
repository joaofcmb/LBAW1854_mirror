<section class="task card float-sm-left p-2 m-2 mt-3">
    <h5 class="card-title text-center pb-2 mb-2" style="border-color: {{$task->color}}">{{$task->project_name}}</h5>
    <h6 class="text-center mb-auto"><a href="">{{$task->title}}</a></h6>

    <p class="ml-1 m-0">{{$task->teams}} Teams</p>
    <p class="ml-1 mb-2">{{$task-> developers}} Developers</p>

    <div class="work-progress mx-2 mb-1">
        <h6 class="text-center mb-1"><i class="fas fa-chart-line mr-1"></i>{{ $task->progress }}% done</h6>
        <div class="progress">
            <div class="progress-bar progress-bar-striped bg-success progress-bar-animated"
                 role="progressbar" style="width:{{ $task->progress }}%" aria-valuenow="{{ $task->progress }}" aria-valuemin="0"
                 aria-valuemax="100"></div>
        </div>
    </div>
    <div class="time-progress mx-2 my-1">
        <h6 class="text-center mb-1"><i class="far fa-clock mr-1"></i>{{$task->timeLeft }} days left</h6>
        <div class="progress">
            <div class="progress-bar progress-bar-striped bg-{{$task->timePercentage == 100 ? 'danger' : 'warning'  }}progress-bar-animated"
                 role="progressbar" style="width:{{ $task->timePercentage }}%" aria-valuenow="{{ $task->timePercentage }}" aria-valuemin="0" aria-valuemax="100">
            </div>
        </div>
    </div>
</section>