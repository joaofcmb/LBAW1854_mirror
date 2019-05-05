@foreach($tabs as $tab)
    @if(count($tab['content']) == 0 && !$onProject)
        @continue
    @endif

    @if($loop->last)
        <div class="main-tab card {{isset($tab['open']) ? 'open ' : ''}}border-left-0 border-right-0 rounded-0 p-2">
    @else
        <div class="main-tab card {{isset($tab['open']) ? 'open ' : ''}}border-left-0 border-right-0 border-bottom-0 rounded-0 p-2">
    @endif

    @include('partials.collapsableTab', ['index' => str_replace(' ', '', $tab['title']), 'tab' => $tab, 'onProject' => $onProject])
    </div>
@endforeach