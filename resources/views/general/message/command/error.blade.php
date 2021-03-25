@if (isset($error[$command]))
<pre id="{{ $tab->getDomId($command, 'error') }}" class="margin-top-20 alert alert-danger">{{ $error[$command] }}</pre>
@else
<span id="{{ $tab->getDomId($command, 'error') }}"></span>
@endif