@if (isset($error[$command]))
<pre id="{{ $tab->makeDomId($command, 'error') }}" class="margin-top-20 alert alert-danger">{{ $error[$command] }}</pre>
@else
<span id="{{ $tab->makeDomId($command, 'error') }}"></span>
@endif