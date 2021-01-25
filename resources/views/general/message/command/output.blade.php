@if (isset($output[$command]))
<pre id="{{ $tab->getDomId($command, 'output') }}" class="margin-top-20">{{ $output[$command] }}</pre>
@else
<span id="{{ $tab->getDomId($command, 'output') }}"></span>
@endif