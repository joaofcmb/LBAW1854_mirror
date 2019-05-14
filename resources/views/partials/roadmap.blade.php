@if($page == 'overview')
    <div class="roadmap m-sm-5 my-4">
        <div class="roadmap-left"></div>
        <div class="roadmap-diagram border-top-0 border-bottom-0 d-flex justify-content-between align-items-center">
            <div class="p-1"></div>
                @foreach($milestones as $milestone)
                    <div class="milestone py-2"><i class="far fa-{{ $milestone->deadline < $date ? 'dot-' : '' }}circle align-middle"></i></div>
                @endforeach
            <div class="p-1"></div>
        </div>
        <div class="roadmap-right"></div>
        @if($currentMilestone != null)
        <div class="milestone-description mx-auto text-center">

            {{ $currentMilestone->timeLeft }} days left
            <h6>{{ $currentMilestone->name }}</h6>
        </div>
        @endif
    </div>
@elseif ($page == 'roadmap')
    <div class="roadmap my-5 mx-sm-5">
        <div class="roadmap-left"></div>
        <div class="roadmap-diagram border-top-0 border-bottom-0 d-flex justify-content-between align-items-center">
            <div class="p-1"></div>
            @foreach($milestones as $milestone)
                <a id="{{ $milestone->id_project }}-milestone1-{{ $milestone->id }}" data-toggle="collapse"  
                    class="milestone-switch milestone py-2"><i class="far fa-{{ $milestone->deadline < $date ? 'dot-' : '' }}circle align-middle"></i></a>
            @endforeach
            <div class="p-1"></div>
        </div>
        <div class="roadmap-right"></div>
        <div id="all-milestones" class="d-none d-lg-flex justify-content-between align-items-center">
            <div class="p-4"></div>
            @foreach($milestones as $milestone)
                <a id="{{ $milestone->id_project }}-milestone-{{ $milestone->id }}" data-toggle="collapse"
                    @if ($currentMilestone != null)
                        {{ $milestone->id == $currentMilestone->id ? 'aria-expanded=true' : '' }}
                        class="milestone-switch milestone-info {{ $milestone->id == $currentMilestone->id ? 'active' : 'collapsed'}} text-center pb-3"
                    @else
                        class="milestone-switch collapsed milestone-info text-center pb-3"
                    @endif
                    style="border-color: rgb(12, 116, 214);">
                    <h6 class="mb-1">{{ date_format(date_create($milestone->deadline), 'Y-m-d') }}</h6>
                    {{ $milestone->deadline < $date ? 'Elapsed' : $milestone->timeLeft . ' days left' }}
                </a>
            @endforeach
            <div class="p-4"></div>
        </div>
    </div>
@endif