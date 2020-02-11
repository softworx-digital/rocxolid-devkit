@if (isset($output[$command]))
<pre id="{{ $tab->makeDomId($command, 'output') }}" class="margin-top-20">{{ $output[$command] }}</pre>
@else
<span id="{{ $tab->makeDomId($command, 'output') }}"></span>
@endif