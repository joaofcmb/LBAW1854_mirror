<!-- @foreach($tabs as $tab)
    @if(count($tab['content']) == 0)
        @continue
    @endif

    @if($loop -> last)
        <div class="main-tab card border-left-0 border-right-0 rounded-0 p-2">
            @include('partials.collapsableTab', ['index' => $loop->index, 'tab' => $tab])
        </div>
    @else
        <div class="main-tab card border-left-0 border-right-0 border-bottom-0 rounded-0 p-2">
            @include('partials.collapsableTab', ['index' => $loop->index, 'tab' => $tab])
        </div>
    @endif
@endforeach -->