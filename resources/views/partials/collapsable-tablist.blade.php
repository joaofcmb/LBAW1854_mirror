@foreach($tabs as $tab)
    @if(count($tab['content']) == 0)
        @continue
    @endif

    @if($loop->last)
        <div class="main-tab card {{isset($tab['open']) ? 'open ' : ''}}border-left-0 border-right-0 rounded-0 p-2">
            @include('partials.collapsable-tab', ['index' => str_replace(' ', '', $tab['title']), 'tab' => $tab])
        </div>
    @else
        <div class="main-tab card {{isset($tab['open']) ? 'open ' : ''}}border-left-0 border-right-0 border-bottom-0 rounded-0 p-2">
            @include('partials.collapsable-tab', ['index' => str_replace(' ', '', $tab['title']), 'tab' => $tab])
        </div>
    @endif
@endforeach